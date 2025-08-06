<div>
    <form action="{{ getRouteForPage('delegate-store') ?? '#' }}" method="POST" autocomplete="off"
        enctype="multipart/form-data" class="bg-white h-full w-full rounded-lg border-0 p-6 mb-10">
        @csrf
        @php
            function buildOptionsHtml($items)
            {
                $html = '';
                foreach ($items as $item) {
                    if (is_object($item)) {
                        $value = $item->id;
                        $label = $item->value;
                    } else {
                        $value = $label = $item;
                    }
                    $html .= '<option value="' . e($value) . '">' . e($label) . '</option>';
                }
                return $html;
            }

            $genderDropdown = getDropDown('gender');
            $genderOptions = $genderDropdown?->options ?? collect();
            $genderOptionsHtml = buildOptionsHtml($genderOptions);

            $relationshipDropdown = getDropDown('relationship');
            $relationshipOptions = $relationshipDropdown?->options ?? collect();
            $relationshipOptionsHtml = buildOptionsHtml($relationshipOptions);

            $titleDropdown = getDropDown('title');
            $titleOptions = $titleDropdown?->options ?? collect();
            $titleOptionsHtml = buildOptionsHtml($titleOptions);

            $internalRankingDropdown = getDropDown('internal_ranking');
            $internalRankingOptions = $internalRankingDropdown?->options ?? collect();
            $internalRankingOptionsHtml = buildOptionsHtml($internalRankingOptions);

            $delegatesData = old('delegates')
                ? array_values(
                    array_map(
                        function ($d, $idx) {
                            return [
                                'tmp_id' => $d['tmp_id'] ?? $idx + 1,
                                'title_id' => $d['title_id'] ?? '',
                                'name_ar' => $d['name_ar'] ?? '',
                                'name_en' => $d['name_en'] ?? '',
                                'designation_en' => $d['designation_en'] ?? '',
                                'designation_ar' => $d['designation_ar'] ?? '',
                                'gender_id' => $d['gender_id'] ?? '',
                                'parent_id' => $d['parent_id'] ?? '',
                                'relationship' => $d['relationship'] ?? '',
                                'internal_ranking_id' => $d['internal_ranking_id'] ?? '',
                                'note' => $d['note'] ?? '',
                                'team_head' => !empty($d['team_head']),
                                'badge_printed' => !empty($d['badge_printed']),
                            ];
                        },
                        old('delegates'),
                        array_keys(old('delegates')),
                    ),
                )
                : [
                    [
                        'tmp_id' => 1,
                        'title_id' => '',
                        'name_ar' => '',
                        'name_en' => '',
                        'designation_en' => '',
                        'designation_ar' => '',
                        'gender_id' => '',
                        'parent_id' => '',
                        'relationship' => '',
                        'internal_ranking_id' => '',
                        'note' => '',
                        'team_head' => false,
                        'badge_printed' => false,
                    ],
                ];
        @endphp

        <div class="space-y-4" x-data="delegateComponent()">
            <div id="delegate-container">
                <template x-for="(delegate, index) in delegates" :key="`delegate-${delegate.tmp_id}`">
                    <div>
                        <input type="hidden" :name="`delegates[${index}][tmp_id]`" :value="delegate.tmp_id" />

                        <div class="delegate-row border rounded p-4 grid grid-cols-12 gap-4 relative">
                            <button type="button"
                                class="delete-row absolute top-2 end-2 text-red-600 hover:text-red-800"
                                title="Remove delegate" @click="removeDelegate(index)"
                                x-show="delegates.length > 1">&times;</button>

                            <!-- Title -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('title') }}</label>
                                <select :name="`delegates[${index}][title_id]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.title_id">
                                    <option value="">{{ __db('select_title') }}</option>
                                    {!! $titleOptionsHtml !!}
                                </select>
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.title_id`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.title_id`][0]"></span>
                                </div>
                            </div>

                            <!-- Name (Arabic) -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('name_ar') }}</label>
                                <input type="text" :name="`delegates[${index}][name_ar]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.name_ar">
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.name_ar`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.name_ar`][0]"></span>
                                </div>
                            </div>

                            <!-- Name (English) -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('name_en') }}</label>
                                <input type="text" :name="`delegates[${index}][name_en]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.name_en">
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.name_en`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.name_en`][0]"></span>
                                </div>
                            </div>

                            <!-- Designation (English) -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('designation_en') }}</label>
                                <input type="text" :name="`delegates[${index}][designation_en]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.designation_en">
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.designation_en`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.designation_en`][0]"></span>
                                </div>
                            </div>

                            <!-- Designation (Arabic) -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('designation_ar') }}</label>
                                <input type="text" :name="`delegates[${index}][designation_ar]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.designation_ar">
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.designation_ar`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.designation_ar`][0]"></span>
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('gender') }}</label>
                                <select :name="`delegates[${index}][gender_id]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.gender_id">
                                    <option value="">{{ __db('select_gender') }}</option>
                                    {!! $genderOptionsHtml !!}
                                </select>
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.gender_id`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.gender_id`][0]"></span>
                                </div>
                            </div>

                            <!-- Parent -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('parent') }}</label>
                                <select :name="`delegates[${index}][parent_id]`" x-model="delegate.parent_id"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                    <option value="">{{ __db('select_parent_id') }}</option>
                                    <template x-for="(parentDelegate, parentIndex) in delegates"
                                        :key="`parent-${parentDelegate.tmp_id}`">
                                        <template x-if="parentDelegate.tmp_id !== delegate.tmp_id">
                                            <option :value="parentDelegate.tmp_id">
                                                Parent #<span x-text="parentIndex + 1"></span> - <span
                                                    x-text="parentDelegate.name_en || parentDelegate.name_ar || 'Unnamed'"></span>
                                            </option>
                                        </template>
                                    </template>
                                </select>
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.parent_id`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.parent_id`][0]"></span>
                                </div>
                            </div>

                            <!-- Relationship -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('relationship') }}</label>
                                <select :name="`delegates[${index}][relationship]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.relationship">
                                    <option value="">{{ __db('select_relationship') }}</option>
                                    {!! $relationshipOptionsHtml !!}
                                </select>
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.relationship`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.relationship`][0]"></span>
                                </div>
                            </div>

                            <!-- Internal Ranking -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('internal_ranking') }}</label>
                                <select :name="`delegates[${index}][internal_ranking_id]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.internal_ranking_id">
                                    <option value="">{{ __db('select_internal_ranking') }}</option>
                                    {!! $internalRankingOptionsHtml !!}
                                </select>
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.internal_ranking_id`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.internal_ranking_id`][0]"></span>
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="col-span-6">
                                <label class="form-label">{{ __db('note') }}</label>
                                <textarea :name="`delegates[${index}][note]`" rows="3"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600" x-model="delegate.note"></textarea>
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.note`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.note`][0]"></span>
                                </div>
                            </div>

                            <!-- Checkboxes -->
                            <span class="col-span-12 border-t border-neutral-200 pt-6 mt-6 flex gap-8">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" :id="`team-head-${index}`"
                                        :name="`delegates[${index}][team_head]`" value="1"
                                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        x-model="delegate.team_head" />
                                    <label :for="`team-head-${index}`"
                                        class="text-sm text-gray-700">{{ __db('team_head') }}</label>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" :id="`badge-printed-${index}`"
                                        :name="`delegates[${index}][badge_printed]`" value="1"
                                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        x-model="delegate.badge_printed" />
                                    <label :for="`badge-printed-${index}`"
                                        class="text-sm text-gray-700">{{ __db('badge_printed') }}</label>
                                </div>
                            </span>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex justify-between items-center mt-5">
                <button type="button" id="add-delegate-btn"
                    class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12 px-6" @click="addDelegate()">
                    {{ __db('add_delegate') }}
                </button>

                <div class="flex gap-4">
                    <button type="submit" name="submit_exit"
                        class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12 px-8">{{ __db('submit') }}</button>
                    <button type="submit" name="submit_add_travel" value="1"
                        class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12 px-8">{{ __db('submit_add_flight') }}</button>
                    <button type="submit" name="submit_add_interview" value="1"
                        class="btn text-md !bg-[#D7BC6D] text-white rounded-lg h-12 px-8">{{ __db('submit_add_interview') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>


@section('script')
    <script>
        function delegateComponent() {
            return {
                delegates: window.delegatesData && window.delegatesData.length > 0 ?
                    window.delegatesData : [{
                        tmp_id: 1,
                        title_id: '',
                        name_ar: '',
                        name_en: '',
                        designation_en: '',
                        designation_ar: '',
                        gender_id: '',
                        parent_id: '',
                        relationship: '',
                        internal_ranking_id: '',
                        note: '',
                        team_head: false,
                        badge_printed: false
                    }],
                addDelegate() {
                    const maxTmpId = Math.max(...this.delegates.map(d => d.tmp_id || 0), 0);
                    const newTmpId = maxTmpId + 1;
                    this.delegates.push({
                        tmp_id: newTmpId,
                        title_id: '',
                        name_ar: '',
                        name_en: '',
                        designation_en: '',
                        designation_ar: '',
                        gender_id: '',
                        parent_id: '',
                        relationship: '',
                        internal_ranking_id: '',
                        note: '',
                        team_head: false,
                        badge_printed: false
                    });
                },
                removeDelegate(idx) {
                    if (this.delegates.length > 1) {
                        this.delegates.splice(idx, 1);
                    }
                }
            }
        }
    </script>
@endsection
