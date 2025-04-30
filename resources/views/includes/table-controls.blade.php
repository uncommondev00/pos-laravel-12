<div class="row mb-3">
    <div class="col-sm-12 col-md-6">
        <div class="dataTables_length">
            <label>
                Show
                <select wire:model.live="perPage" class="form-control form-control-sm" style="width: auto; display: inline-block;">
                    @foreach($perPageOptions as $option)
                    <option value="{{ $option }}">{{ $option === -1 ? 'All' : $option }}</option>
                    @endforeach
                </select>
                entries
            </label>
        </div>
    </div>
    <div class="col-sm-6 text-right">
        <div class="dataTables_filter">
            <label>
                Search:
                <div class="input-group" style="display: inline-flex; width: auto;">
                    <input type="search"
                        wire:model.live.debounce.500ms="search"
                        class="form-control form-control-sm"
                        placeholder="Type to search..."
                        style="width: 200px;">
                    @if($search)
                    <div class="input-group-append">
                        <button wire:click="$set('search', '')" class="btn btn-sm btn-default">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </label>
        </div>
    </div>
</div>
