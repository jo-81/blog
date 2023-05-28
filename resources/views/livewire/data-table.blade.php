<div x-data="{selection: @entangle('selection').defer}">

    <div class="mb-6">
        <input  class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                type="search" 
                placeholder="Rechercher"
                wire:model.debounde.500ms="search"/>
    </div>

    <table class="border-collapse table-auto w-full text-gray-700">
        <thead class="text-left border-b border-gray-300 font-bold">
            <tr>
                @foreach($labels as $label)
                    <x-table-header 
                        :direction="$orderDirection" 
                        :name="$label" 
                        :field="$orderField">{{ ucfirst(__($label)) }}</x-table-header>
                @endforeach
                <th class="p-3">Actions</th>
            </tr>
        </thead>

        <tbody class="text-sm">
            @foreach ($items as $item)
                <tr class="border-b border-gray-300">
                    @foreach($labels as $label)
                        <td class="p-3">{{ $item->$label }}</td>
                    @endforeach
                        <td class="p-3">
                            <a href="{{ route($routeShow, ['id' => $item->id]) }}">Consulter</a>
                        </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
</div>
