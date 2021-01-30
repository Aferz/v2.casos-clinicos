<div class="col-span-1 lg:mt-0 lg:w-96">
    <div
        class="bg-white rounded-md py-8 border block lg:sticky"
        style="top: 2rem;"
    >
        <img
            class="h-24 w-24 rounded-full ring-2 ring-primary-500 ring-offset-2 mx-auto"
            src="{{ asset("images/users/{$clinicalCase->user->avatar_path}") }}"
        />

        <div class="text-center text-lg mt-4 font-medium text-primary-600">
            {{ $clinicalCase->user->fullname }}
        </div>
    </div>
</div>