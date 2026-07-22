<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry">
    @php
    $file = $getState();

    $extension = strtolower(pathinfo($file ?? '', PATHINFO_EXTENSION));

    $isPdf = $extension === 'pdf';

    $isImage = in_array($extension, [
    'png',
    'jpg',
    'jpeg',
    'gif',
    'webp',
    'bmp',
    'svg',
    ]);

    $url = $file ? Storage::url($file) : null;
    @endphp

    <div
        class="w-full border border-gray-300 rounded-lg overflow-hidden flex flex-col bg-white"
        @contextmenu.prevent>
        @if ($file)

        {{-- Toolbar --}}
        <div class="flex items-center gap-2 p-2 border-b bg-gray-50">

            <button
                type="button"
                class="px-3 py-1 text-sm border rounded"
                @click="zoom = Math.max(zoomMin, zoom - zoomStep)">
                -
            </button>

            <span
                class="text-sm font-medium min-w-17.5] text-center"
                x-text="Math.round(zoom * 100) + '%'"></span>

            <button
                type="button"
                class="px-3 py-1 text-sm border rounded"
                @click="zoom = Math.min(zoomMax, zoom + zoomStep)">
                +
            </button>

            <button
                type="button"
                class="px-3 py-1 text-sm border rounded"
                @click="zoom = 1">
                Reset
            </button>
        </div>

        {{-- Viewer --}}
        <div
            class="overflow-auto bg-gray-100"
            style="height: 700px;"
            @wheel.ctrl.prevent="
                    zoom = Math.max(
                        zoomMin,
                        Math.min(
                            zoomMax,
                            zoom + ($event.deltaY < 0 ? zoomStep : -zoomStep)
                        )
                    )
                ">

            {{-- PDF --}}
            @if ($isPdf)
            <iframe
                src="{{ Storage::url($getState()) }}#toolbar=0"
                class="bg-white shadow"
                style="
                        width: 100%;
                        height: 500px;
                    "></iframe>


            {{-- IMAGE --}}
            @elseif ($isImage)
            <div class="inline-block min-w-full min-h-full p-8">
                <img
                    src="{{ Storage::url($getState()) }}"
                    alt="Preview"
                    class="max-w-none transition-transform duration-100 ease-out"
                    :style="`transform: scale(${zoom}); transform-origin: top center;`" />
            </div>
            {{-- UNSUPPORTED --}}
            @else

            <div class="p-8 text-center">
                <p class="text-gray-500">
                    Preview not available for this file type.
                </p>

                {{ $url }} target="_blank"
                class="text-primary-600 underline"
                >
                Download File
                </a>
            </div>

            @endif

        </div>

        @else

        <div class="p-4 text-gray-500">
            No attachment available.
        </div>

        @endif
    </div>
</x-dynamic-component>