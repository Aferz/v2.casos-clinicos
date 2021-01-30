<x-layout.auth>
    <div class="max-w-2xl w-full justify-center px-4 bg-gray-100 mx-auto my-8">
        <form
            class="bg-white border rounded-md p-8 mt-8"
            action="{{ route('accept-terms.accept') }}"
            method="POST"
            x-data="{ sanedRules: false, labRules: false }"
        >
            @csrf

            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Terms & Conditions') }}
                </h3>

                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ __('Before continue you must accept the terms and conditions of the platform') }}.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 mt-8">
                <div class="col-span-1">
                    <x-clinical-case.checkbox-rules-saned
                        required
                        tabindex="1"
                        name="saned_rules"
                        x-model="sanedRules"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 mt-8">
                <div class="col-span-1">
                    <x-clinical-case.checkbox-rules-lab
                        required
                        tabindex="2"
                        name="lab_rules"
                        x-model="labRules"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 mt-8">
                <div class="col-span-1">
                    <x-clinical-case.checkbox-rules-saned-mailing
                        tabindex="3"
                        name="saned_rules_mailing"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 mt-8">
                <div class="col-span-1">
                    <x-clinical-case.checkbox-rules-lab-mailing
                        tabindex="4"
                        name="lab_rules_mailing"
                    />
                </div>
            </div>

            <div class="flex justify-between items-center mt-16">
                <x-button.link
                    href="{{ route('login') }}"
                    tabindex="-1"
                    onclick="document.forms.reject.submit()"
                >
                    {{ '<- ' . __('Cancel') }}
                </x-button.link>

                <x-button.button
                    tabindex="4"
                    disabled
                    x-bind:disabled="!sanedRules || !labRules"
                >
                    {{ __('Accept & Continue') }}
                </x-button.button>
            </div>
        </form>

        <form
            id="reject"
            method="POST"
            action="{{ route('accept-terms.reject') }}"
        >
            @method('DELETE')
            @csrf
        </form>
    </div>
</x-layout.auth>