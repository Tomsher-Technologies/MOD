@php
    $statusLabels = [
        'arrived' => __db('arrived'),
        'to_be_arrived' => __db('to_be_arrived'),
    ];

    $columns = [
        [
            'label' => __db('sl_no'),
            // 'class' => 'text-[16px]',
            'render' => fn($row, $key) => $key +
                1 +
                (($paginator ?? $arrivals)->currentPage() - 1) * ($paginator ?? $arrivals)->perPage(),
        ],
        [
            'label' => __db('delegation'),
            // 'class' => 'text-[16px]',
            'render' => function ($row) {
                $delegationId = $row['delegation']->id ?? null;
                $delegationCode = $row['delegation']->code ?? '-';

                if ($delegationId && $delegationCode !== '-') {
                    $viewUrl = route('delegations.show', $delegationId);
                    return '<a href="' .
                        $viewUrl .
                        '" class="text-[#B68A35] hover:underline">' .
                        e($delegationCode) .
                        '</a>';
                }

                return $delegationCode;
            },
        ],
        [
            'label' => __db('continent'),
            // 'class' => 'text-[16px]',
            'render' => fn($row) => $row['delegation']->continent->value ?? '-',
        ],
        [
            'label' => __db('country'),
            'key' => 'country',
            // 'class' => 'text-[16px]',
            'render' => function ($row) {
                if (!$row['delegation']->country) {
                    return '-';
                }

                $flag = $row['delegation']->country->flag
                    ? '<img src="' .
                        getUploadedImage($row['delegation']->country->flag) .
                        '" 
                        alt="' .
                        e($row['delegation']->country->name) .
                        ' flag" 
                        class="inline-block w-6 h-4 mr-2 rounded-sm object-cover" />'
                    : '';

                return $flag . ' ' . e($row['delegation']->country->name);
            },
        ],
        [
            'label' => __db('invitation_from'),
            // 'class' => 'text-[16px]',
            'render' => fn($row) => $row['delegation']->invitationFrom->value ?? '-',
        ],
        [
            'label' => __db('delegates'),
            // 'class' => 'text-[16px]',
            'render' => function ($row) {
                $delegation = $row['delegation'] ?? null;

                if ($delegation) {
                    $teamHead = null;
                    foreach ($row['delegates'] as $delegate) {
                        if ($delegate->team_head) {
                            $teamHead = $delegate;
                            break;
                        }
                    }

                    if ($teamHead) {
                        return e($teamHead->getTranslation('title') . '. ' . $teamHead->getTranslation('name'));
                    }

                    $firstDelegate = collect($row['delegates'])->first();
                    if ($firstDelegate) {
                        return e(
                            $firstDelegate->getTranslation('title') . '. ' . $firstDelegate->getTranslation('name'),
                        );
                    }
                }

                return '-';
            },
        ],

        [
            'label' => __db('escorts'),
            // 'class' => 'text-[16px]',
            'render' => function ($row) {
                return $row['delegation']->escorts->isNotEmpty()
                    ? $row['delegation']->escorts->map(fn($escort) => e($escort->code))->implode('<br>')
                    : '-';
            },
        ],
        [
            'label' => __db('drivers'),
            // 'class' => 'text-[16px]',
            'render' => function ($row) {
                return $row['delegation']->drivers->isNotEmpty()
                    ? $row['delegation']->drivers->map(fn($drivers) => e($drivers->code))->implode('<br>')
                    : '-';
            },
        ],
        [
            'label' => __db('to_airport'),
            // 'class' => 'text-[16px]',
            'render' => fn($row) => $row['airport']->value ?? '-',
        ],
        [
            'label' => __db('date_time'),
            // 'class' => 'text-[16px]',
            'render' => fn($row) => $row['date_time']
                ? \Carbon\Carbon::parse($row['date_time'])->format('Y-m-d H:i')
                : '-',
        ],
        [
            'label' => __db('flight') . ' ' . __db('number'),
            // 'class' => 'text-[16px]',
            'render' => fn($row) => $row['flight_no'] ?? '-',
        ],
        [
            'label' => __db('flight') . ' ' . __db('name'),
            // 'class' => 'text-[16px]',
            'render' => fn($row) => $row['flight_name'] ?? '-',
        ],
        [
            'label' => __db('arrival') . ' ' . __db('status'),
            // 'class' => 'text-[16px]',
            'render' => function ($row) use ($statusLabels) {
                return $row['status'] ?? '-';
            },
        ],
        [
            'label' => __db('actions'),
            // 'class' => 'text-[16px]',
            'permission' => ['add_travels', 'delegate_add_delegates'],
            'render' => function ($row) {
                $transportIds = collect($row['transports'])->pluck('id')->toArray();
                if (empty($transportIds)) {
                    return '-';
                }

                $firstTransport = $row['transports'][0];
                $delegationId = $row['delegation']->id ?? null;

                $arrivalData = [
                    'ids' => $transportIds,
                    'airport_id' => $firstTransport->airport_id,
                    'flight_no' => $firstTransport->flight_no,
                    'flight_name' => $firstTransport->flight_name,
                    'date_time' => $firstTransport->date_time
                        ? \Carbon\Carbon::parse($firstTransport->date_time)->format('Y-m-d H:i')
                        : '',
                    'status' => $firstTransport->status,
                ];
                $json = htmlspecialchars(json_encode($arrivalData), ENT_QUOTES, 'UTF-8');

                $viewButton = '';
                if ($delegationId) {
                    $viewUrl = route('delegations.show', $delegationId);
                    $viewButton =
                        '<a href="' .
                        $viewUrl .
                        '" class="w-8 h-8 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center" title="' .
                        __db('view_delegation') .
                        '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#B68A35" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </a>';
                }

                $editButton =
                    '<button type="button" class="edit-arrival-btn w-8 h-8 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center ml-1" data-arrival=\'' .
                    $json .
                    '\'><svg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' viewBox=\'0 0 512 512\'><path d=\'M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z\' fill=\'#B68A35\'></path></svg>
                </button>';

                return $viewButton . $editButton;
            },
        ],
    ];

    $bgClass = [
        'to_be_arrived' => 'bg-[#fff]',
        'arrived' => 'bg-[#b7e9b2]',
    ];

    $rowClass = function ($row) use ($bgClass) {
        $now = \Carbon\Carbon::now();

        $statusName =
            is_object($row['status']) && isset($row['status']->value) ? $row['status']->value : $row['status'];

        if (!$row['date_time']) {
            return $bgClass[$statusName] ?? 'bg-[#fff]';
        }

        $rowDateTime = \Carbon\Carbon::parse($row['date_time']);

        if ($statusName === 'to_be_arrived') {
            if ($rowDateTime->between($now->copy()->subHour(), $now->copy()->addHour())) {
                return 'bg-[#ffc5c5]';
            }

            if ($rowDateTime->gt($now->copy()->addHour())) {
                return 'bg-[#fff]';
            }
        }

        if ($statusName === 'arrived') {
            return $bgClass['arrived'];
        }

        return $bgClass[$statusName] ?? 'bg-[#fff]';
    };
@endphp

<div id="arrivals-table-container">
    <x-reusable-table :columns="$columns" :enableRowLimit="true" :enableColumnListBtn="true" table-id="arrivals-table" :data="$paginator"
        :row-class="$rowClass" />
</div>

<div class="mt-3 flex items-center flex-wrap gap-4">
    <div class="flex items-center gap-2">
        <div class="h-5 w-5 bg-[#fff] rounded border border-gray-300"></div>
        <span class="text-gray-800 text-sm">{{ __db('scheduled_no_active_status') }}</span>
    </div>

    <div class="flex items-center gap-2">
        <div class="h-5 w-5 bg-[#b7e9b2] rounded border"></div>
        <span class="text-gray-800 text-sm">{{ __db('arrived') }}</span>
    </div>

    <div class="flex items-center gap-2">
        <div class="h-5 w-5 bg-[#ffc5c5] rounded border"></div>
        <span class="text-gray-800 text-sm">{{ __db('to_be_arrived_within_1_hour') }}</span>
    </div>
</div>
