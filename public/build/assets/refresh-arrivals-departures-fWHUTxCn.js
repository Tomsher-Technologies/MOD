document.addEventListener("DOMContentLoaded",function(){const i=document.getElementById("fullDiv")!==null,v=document.getElementById("fullDiv1")!==null;if(!i&&!v)return;function p(){console.log("refreshing");const r=new URL(window.location.href).toString();fetch(r,{method:"GET",headers:{"X-Requested-With":"XMLHttpRequest",Accept:"application/json"}}).then(e=>e.json()).then(e=>{if(e.success&&e.data){const l=i?document.getElementById("arrivals-table-container"):document.getElementById("departures-table-container");if(l){const n=document.createElement("div");n.innerHTML=e.data;const a=n.querySelector("table"),s=n.querySelector(".mt-3.flex.items-center.flex-wrap.gap-4");if(n.querySelector('[id$="-table-column-visibility-modal"]'),a){a.classList.remove("hidden"),(document.fullscreenElement||document.webkitFullscreenElement||document.mozFullScreenElement||document.msFullscreenElement)&&a.classList.add("fullscreen-table");const m=l.querySelector("table");if(m?m.replaceWith(a):(l.innerHTML="",l.appendChild(a)),s){const o=l.nextElementSibling;o&&o.classList.contains("mt-3")?o.replaceWith(s):l.parentNode.insertBefore(s,l.nextSibling)}f(),c(),setTimeout(()=>{typeof $<"u"&&$.fn.select2&&$(".select2").select2({placeholder:"Select an option",allowClear:!0}),u()},100)}}console.log("Data refreshed at: "+new Date().toLocaleTimeString())}}).catch(e=>{console.error("Error refreshing data:",e)})}function u(){document.querySelectorAll(".edit-arrival-btn").forEach(e=>{e.addEventListener("click",()=>{const l=JSON.parse(e.getAttribute("data-arrival"));window.dispatchEvent(new CustomEvent("open-edit-arrival",{detail:l}))})}),document.querySelectorAll(".edit-departure-btn").forEach(e=>{e.addEventListener("click",()=>{const l=JSON.parse(e.getAttribute("data-departure"));window.dispatchEvent(new CustomEvent("open-edit-departure",{detail:l}))})});const t=document.getElementById("fullscreenToggleBtn");t&&t.addEventListener("click",d);const r=document.getElementById("fullscreenToggleBtn1");r&&r.addEventListener("click",d)}function c(){document.querySelectorAll('[id$="-column-toggles"]').forEach(t=>{const r=t.id.replace("-column-toggles",""),e=r+"_column_visibility",l=JSON.parse(localStorage.getItem(e));l&&Object.keys(l).forEach(n=>{const a=l[n];document.querySelectorAll(`#${r} th[data-column-key='${n}'], #${r} td[data-column-key='${n}']`).forEach(b=>{b.style.display=a?"":"none"});const s=t.querySelector(`.column-toggle-checkbox[value="${n}"]`);s&&(s.checked=a)})})}function d(){const t=document.getElementById("fullDiv")||document.getElementById("fullDiv1"),r=document.querySelectorAll(".full-screen-logo");document.fullscreenElement?(document.exitFullscreen?document.exitFullscreen():document.webkitExitFullscreen?document.webkitExitFullscreen():document.msExitFullscreen&&document.msExitFullscreen(),r.forEach(e=>e.classList.add("hidden"))):(t.requestFullscreen?t.requestFullscreen():t.webkitRequestFullscreen?t.webkitRequestFullscreen():t.msRequestFullscreen&&t.msRequestFullscreen(),r.forEach(e=>e.classList.remove("hidden")))}function f(){if(!document.getElementById("fullscreen-table-styles")){const t=document.createElement("style");t.id="fullscreen-table-styles",t.textContent=`
                :fullscreen #fullDiv table#arrivals-table thead tr,
                :fullscreen #fullDiv table#departures-table thead tr,
                :fullscreen #fullDiv1 table#arrivals-table thead tr,
                :fullscreen #fullDiv1 table#departures-table thead tr {
                    font-size: 20px !important;
                }
                
                :fullscreen #fullDiv table#arrivals-table tbody tr,
                :fullscreen #fullDiv table#departures-table tbody tr,
                :fullscreen #fullDiv1 table#arrivals-table tbody tr,
                :fullscreen #fullDiv1 table#departures-table tbody tr {
                    font-size: 18px !important;
                }
                
                /* For webkit browsers */
                ::-webkit-full-screen #fullDiv table#arrivals-table thead tr,
                ::-webkit-full-screen #fullDiv table#departures-table thead tr,
                ::-webkit-full-screen #fullDiv1 table#arrivals-table thead tr,
                ::-webkit-full-screen #fullDiv1 table#departures-table thead tr {
                    font-size: 20px !important;
                }
                
                ::-webkit-full-screen #fullDiv table#arrivals-table tbody tr,
                ::-webkit-full-screen #fullDiv table#departures-table tbody tr,
                ::-webkit-full-screen #fullDiv1 table#arrivals-table tbody tr,
                ::-webkit-full-screen #fullDiv1 table#departures-table tbody tr {
                    font-size: 18px !important;
                }
                
                /* For mozilla browsers */
                :-moz-full-screen #fullDiv table#arrivals-table thead tr,
                :-moz-full-screen #fullDiv table#departures-table thead tr,
                :-moz-full-screen #fullDiv1 table#arrivals-table thead tr,
                :-moz-full-screen #fullDiv1 table#departures-table thead tr {
                    font-size: 20px !important;
                }
                
                :-moz-full-screen #fullDiv table#arrivals-table tbody tr,
                :-moz-full-screen #fullDiv table#departures-table tbody tr,
                :-moz-full-screen #fullDiv1 table#arrivals-table tbody tr,
                :-moz-full-screen #fullDiv1 table#departures-table tbody tr {
                    font-size: 18px !important;
                }
                
                /* For MS browsers */
                :-ms-fullscreen #fullDiv table#arrivals-table thead tr,
                :-ms-fullscreen #fullDiv table#departures-table thead tr,
                :-ms-fullscreen #fullDiv1 table#arrivals-table thead tr,
                :-ms-fullscreen #fullDiv1 table#departures-table thead tr {
                    font-size: 20px !important;
                }
                
                :-ms-fullscreen #fullDiv table#arrivals-table tbody tr,
                :-ms-fullscreen #fullDiv table#departures-table tbody tr,
                :-ms-fullscreen #fullDiv1 table#arrivals-table tbody tr,
                :-ms-fullscreen #fullDiv1 table#departures-table tbody tr {
                    font-size: 18px !important;
                }
                
                .fullscreen-table thead tr {
                    font-size: 20px !important;
                }
                
                .fullscreen-table tbody tr {
                    font-size: 18px !important;
                }
            `,document.head.appendChild(t)}}f(),u(),c(),setInterval(p,3e3),console.log("Auto-refresh initialized for "+(i?"arrivals":"departures")+" page")});
