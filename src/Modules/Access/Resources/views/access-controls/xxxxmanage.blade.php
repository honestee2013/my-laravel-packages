<x-core.views::layouts.app>

    <x-slot name="sidebar">
        <x-core.views::layouts.navbars.auth.sidebar moduleName="access">
            <x-access.views::layouts.navbars.auth.sidebar-links />
        </x-core.views::layouts.navbars.auth.sidebar>
    </x-slot>


    <div class="card mb-4 mx-4 p-4">
        <div class="card-header pb-0">
            <h5 class="mb-4">{{ __('Manage Role Access') }}</h5>
        </div>

        <button onclick="sayHello()">Say Hello</button>

        <div class="card-body pt-4 p-3">


            @if ($errors->any())
                <div class="mt-3  alert alert-primary alert-dismissible fade show" role="alert">
                    <span class="alert-text text-white">
                        {{ $errors->first() }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                </div>
            @endif
            @if (session('success'))
                <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                    <span class="alert-text text-white">
                        {{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                </div>
            @endif



            <livewire:access.livewire.access-controls.module-selector :selectedScope="$accessController['scope']" :selectedRole="$accessController['id']"
                :selectedModule="$accessController['module']" />



            <div class="row md-d-flex md-justify-content-between p-1 m-2 rounded-3 py-2 bg-gray-100" wire:ignore>

                @if (isset($accessController['resourceNames']))
                    @foreach ($accessController['resourceNames'] as $resourceName)
                        <div class="col-md-6 my-2">
                            <div class="card">
                                <div class="card-header pb-0 px-3">
                                    <div class="row d-flex justify-content-between ps-3 pe-5 px-md-4"
                                        id = "{{ $resourceName }}">
                                        <a class="col-11" data-bs-toggle="collapse" href="#{{ $resourceName }}"
                                            role="button" aria-expanded="false" aria-controls="{{ $resourceName }}">
                                            <h6 class="mb-1">{{ ucfirst($resourceName) }} Management</h6>
                                            <span class="mb-2 text-xs">
                                                What <span
                                                    class="text-dark font-weight-bold">{{ ucfirst($accessController['scope']->name) }}</span>
                                                can do
                                                on <span class="text-dark font-weight-bold">{{ ucfirst($resourceName) }}
                                                    records?</span>
                                            </span>
                                            <div id="what_scope_can_do_on_{{ $resourceName }}" class="pt-0">
                                                <!--<span class="badge rounded-pill bg-gradient-info" style="font-size: 0.6em">View records</span>
                                                                <span class="badge rounded-pill bg-gradient-success" style="font-size: 0.6em">Print records</span>
                                                                <span class="badge rounded-pill bg-gradient-warning" style="font-size: 0.6em">Create records</span>
                                                                <span class="badge rounded-pill bg-gradient-danger" style="font-size: 0.6em">Delete records</span>
                                                                <span class="badge rounded-pill bg-gradient-primary" style="font-size: 0.6em">Export records</span>-->

                                                                
                                                @foreach ($accessController['scope']->permissions as $permission)
                                                    @foreach ($accessController['allControls'] as $control)
                                                        @php
                                                            $permissionName = App\Modules\Access\Controllers\AccessController::getPermissionName(
                                                                $control,
                                                                $resourceName,
                                                            );
                                                        @endphp
                                                        @if (str_contains($permission->name, $permissionName))
                                                            <span
                                                                class="badge rounded-pill bg-gradient-{{ $accessController['controlsCSSClasses'][$control]['bg'] ?? 'primary' }}"
                                                                style="font-size: 0.6em; margin: 0.3em 0.1em">{{ ucfirst($control) }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                            </div>
                                        </a>


                                        <div class="col-1 ">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" id = "toggle-all-{{ $resourceName }}"
                                                    type="checkbox"
                                                    onchange="updatePermission(event, '{{ $accessController['scope']->name }}', '{{ $accessController['scope']->id }}', 'all', '{{ $resourceName }}')"
                                                    @if (isset($accessController['toggleAllPermissionSwitchInitColors'][$resourceName]) &&
                                                            $accessController['toggleAllPermissionSwitchInitColors'][$resourceName]['permissionCount']
                                                    ) checked @endif
                                                    @if (isset($accessController['toggleAllPermissionSwitchInitColors'][$resourceName])) style="background-color: {{ $accessController['toggleAllPermissionSwitchInitColors'][$resourceName]['bg'] }};
                                                                        border: {{ $accessController['toggleAllPermissionSwitchInitColors'][$resourceName]['bg'] }}
                                                                        " @endif />

                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="card-body pt-4 p-2 p-md-4 pt-md-4 mb-2">
                                    <ul class="list-group collapse p-3" id="{{ $resourceName }}">
                                        @foreach ($accessController['allControls'] as $control)
                                            <li
                                                class="list-group-item  border-0  ps-3 pe-5 py-3 my-1 bg-gray-100 border-radius-lg ">
                                                <div class="row d-flex justify-content-between px-3">
                                                    <div class="col-11">
                                                        <p class="mb-3 text-sm">
                                                            <span
                                                                class="text-dark font-weight-bold">{{ ucfirst($accessController['scope']->name) }}</span>
                                                            should be able to <span
                                                                class="font-weight-bold">{{ $control }}</span>
                                                            {{ strtolower($resourceName) }} records
                                                        </p>
                                                    </div>
                                                    <div class="col-1 ">
                                                        <div class="form-check form-switch">
                                                            @php
                                                                $permissionName = App\Modules\Access\Controllers\AccessController::getPermissionName(
                                                                    $control,
                                                                    $resourceName,
                                                                );
                                                                $scopePermissions = $accessController['scope']->getPermissionNames()->toArray();

                                                            @endphp
                                                            <input class="form-check-input" type="checkbox"
                                                                id = "toggle-{{ $control }}-{{ $resourceName }}"
                                                                onchange="updatePermission(event, '{{ $accessController['scope']->name }}', '{{ $accessController['scope']->id }}', '{{ $control }}', '{{ $resourceName }}')"
                                                                {{ in_array($permissionName, $scopePermissions) ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- <div class="d-flex justify-content-end">
                            <button type="submit"
                                class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                        </div> --}}


        </div>
    </div>


    {{-- --- TO BE USED BY JS FOR INITIAL CONFIG OF THE UI ---- --}}
    <span id="accessControllerData" data-accesscontroller="{{ json_encode($accessController) }}"></span>






</x-core.views::layouts.app>






<script>
    const offColor = '#e8ebee'
    const onColor = '#98ec2d';
    const halfOnColor = 'green';

    // Use querySelectorAll to get all elements with the class "form-check-input"
    const allCheckedBoxes = document.querySelectorAll(".form-check-input");

    for (let i = 0; i < allCheckedBoxes.length; i++) {
        let checkBox = allCheckedBoxes[i]; // Declare the variable with let

        if (checkBox.id.indexOf("-all-") == -1) {
            if (checkBox.checked) {
                checkBox.style.backgroundColor = onColor;
                checkBox.style.border = onColor;
            } else {
                checkBox.style.backgroundColor = offColor;
                checkBox.style.border = offColor;
            }
        }
    }


    document.addEventListener('DOMContentLoaded', function() {




        /*const accessControllerDataElement = document.getElementById("accessControllerData");
        if (accessControllerDataElement) {
            const accessControllerData = accessControllerDataElement.dataset.accesscontroller;
            try {
                const accessController = JSON.parse(accessControllerData);
                console.log(accessController);
            } catch (e) {
                console.error("Failed to parse JSON data:", e);
            }
        } else {
            console.error("Element with ID 'accessControllerData' not found.");
        }*/






        function toggleCheckBoxColor(event, data) {

            // Toggle one of the switch color
            const switched = event.target;
            if (switched.checked) {
                switched.style.backgroundColor = onColor;
                switched.style.border = onColor;
            } else {
                switched.style.backgroundColor = offColor;
                switched.style.border = offColor;
            }
        }




        function toggleCheckBoxColor(event, data) {

            // Toggle the individual switch color
            const switched = event.target;
            if (switched.checked) {
                switched.style.backgroundColor = onColor;
                switched.style.border = onColor;
            } else {
                switched.style.backgroundColor = offColor;
                switched.style.border = offColor;
            }


            // Handling the  TOGGLE ALL switch
            toggleAllElementId = "toggle-all-" + data["resource_name"];
            const toggleAllCheckBox = document.getElementById(toggleAllElementId);


            if (event.target.id === toggleAllElementId) { // Toggle all switch color when directly clicked
                for (var i = 0; i < data["allControls"].length; i++) {
                    var switchId = "toggle-" + data["allControls"][i] + "-" + data["resource_name"];

                    var switchElem = document.getElementById(switchId);
                    if (data["controls"].length) {

                        switchElem.checked = true;
                        switchElem.style.backgroundColor = onColor;
                        switchElem.style.border = onColor;
                    } else {
                        switchElem.checked = false;
                        switchElem.style.backgroundColor = offColor;
                        switchElem.style.border = offColor;
                    }
                }

            } else { // Toggle all switch color when individual switches clicked

                // GREEN YELLOW & GRAY Indicating toggle all button state

                if (data["controls"].length === 0) { // OFF
                    toggleAllCheckBox.style.backgroundColor = offColor; // No control color
                    toggleAllCheckBox.style.border = offColor; // No control color
                    toggleAllCheckBox.checked = false; // Uncheck the checkbox

                } else if (data["controls"].length === data["allControls"].length) { // ON
                    toggleAllCheckBox.style.backgroundColor = onColor; // Full control color
                    toggleAllCheckBox.style.border = onColor; // Full control color
                    toggleAllCheckBox.checked = true; // Check the checkbox

                } else { // PARTIALLY ON
                    toggleAllCheckBox.style.backgroundColor = halfOnColor; // Half control color
                    toggleAllCheckBox.style.border = halfOnColor; // Half control color
                    toggleAllCheckBox.checked = true; // Check the checkbox
                }

            }

        }






        /*function navigateToModule(select) {
            alert(select.value);
            //Route::get('/{module}/{scope}/{id}', [AccessController::class, 'manage']) - > name(
                //'access-control.manage');

            const url = select.value;
            if (url) {
                window.location.href = url;
            }
        }*/




        window.updatePermission = function(event, scope, scopeId, control, resourceName) {
            //alert(checkbox.checked+": "+scope+" "+scopeId+" "+control+" "+resourceName);

            const isChecked = event.target.checked;
            const url =
                '{{ route('access-control.update') }}'; // Define your route name for updating permissions

            fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        scope_id: scopeId,
                        scope: scope,
                        control: control,
                        resource_name: resourceName,
                        checked: isChecked
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Handle success or update UI if needed
                    if (data['success']) {

                        // Change background color based on the checkbox state
                        toggleCheckBoxColor(event, data);

                        // Update the list of what role can do
                        const element = document.getElementById("what_scope_can_do_on_" + resourceName);
                        element.innerHTML = "";
                        for (var i = 0; i < data['controls'].length; i++) {
                            //console.log(data['controls'][i]);
                            control = data['controls'][i]; // eg view, edit

                            // Create a new span element
                            var span = document.createElement('span');
                            var controlCSSClass = 'primary';
                            if ((data['controlsCSSClasses'][control]).bg)
                                controlCSSClass = (data['controlsCSSClasses'][control]).bg;

                            span.className = 'badge rounded-pill bg-gradient-' + controlCSSClass;
                            span.style.fontSize = '0.6em';
                            span.style.margin = '0.5em 0.3em';
                            span.textContent = control;

                            // Append the new span element to the parent element
                            element.appendChild(span);
                        }

                    }

                })
                .catch((error) => {
                    // Handle error
                    console.error('Error:', error);
                });
        }








    });
</script>
