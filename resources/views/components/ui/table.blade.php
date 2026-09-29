@props([
    'headers' => [],
    'empty' => 'No hay datos disponibles'
])

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <!-- Wrapper responsive -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">

            <!-- Header -->
            <thead class="bg-gray-50 border-b text-gray-500 uppercase text-xs">
                <tr>
                    @foreach ($headers as $header)
                        <th class="px-4 py-3 font-medium">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <!-- Body -->
            <tbody class="divide-y">

                {{ $slot }}

                @if (trim($slot) === '')
                    <tr>
                        <td colspan="{{ count($headers) }}" class="text-center py-6 text-gray-400">
                            {{ $empty }}
                        </td>
                    </tr>
                @endif

            </tbody>

        </table>
    </div>

</div>
