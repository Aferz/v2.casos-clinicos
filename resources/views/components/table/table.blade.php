<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div class="border overflow-hidden border-b border-gray-200 sm:rounded-md">
            <table
                @classes([
                    'min-w-full divide-y divide-gray-200' => true
                ])
                {{ $attributes }}
            >
                {{ $slot }}
            </table>
        </div>
    </div>
</div>