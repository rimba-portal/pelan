<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div
        class="flex w-full flex-col overflow-hidden rounded-lg border border-gray-300 bg-white select-none"
        @contextmenu.prevent
    >
        @if ($getState())
            {{-- Viewer --}}
            <div class="flex flex-1 items-start justify-center overflow-auto bg-gray-100">
                <iframe
                    src="{{ Storage::url($getState()) }}#toolbar=0"
                    class="bg-white shadow"
                    style="width: 100%; height: 500px"
                ></iframe>
            </div>

        @else
            <p class="p-4 text-gray-500">No PDF available.</p>
        @endif
    </div>
</x-dynamic-component>
