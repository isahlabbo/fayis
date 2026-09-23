<div class="form-row">
    <div class="form-group col-md-4"><label for="sanitary-search">Search</label><input id="sanitary-search" wire:model.debounce.300ms="search" class="form-control" placeholder="Search records"></div>
    <div class="form-group col-md-4"><label for="sanitary-filter-item">Item</label><select id="sanitary-filter-item" wire:model="filterItem" class="form-control"><option value="">All items</option>@foreach($items as $item)<option value="{{ $item->id }}">{{ $item->name }}</option>@endforeach</select></div>
    <div class="form-group col-md-2"><label for="sanitary-from">From date</label><input id="sanitary-from" type="date" wire:model="fromDate" class="form-control"></div>
    <div class="form-group col-md-2"><label for="sanitary-to">To date</label><input id="sanitary-to" type="date" wire:model="toDate" class="form-control"></div>
</div>
