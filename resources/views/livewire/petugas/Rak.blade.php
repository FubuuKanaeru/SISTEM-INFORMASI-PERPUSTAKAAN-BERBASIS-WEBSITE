<div class="row">
    <div class="col-12">
      
        @include('petugas/rak/create')
        @include('petugas/rak/edit')
        @include('petugas/rak/delete')
        @include('adminlte/flash')
        
        <div class="card">
            <div class="card-header">
                <span wire:click="Create" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>Tambah</span>
                 
       
              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <select wire:model.live.throttle.500ms="search" class="form-control float-right" id="exampleFormControlSelect1">
                    @foreach ($count as $item)
                      <option value="{{ $item->rak }}">{{ $item->rak }}</option>
                    @endforeach
                  </select>
                  <div class="input-group-append">
                    <button wire:Click="formatSearch" type="submit" class="btn btn-default">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
            </div>
          </div>
      <!-- /.card-header -->
      @if ($raks->isNotEmpty())
      <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
              <tr>
                <th width="10%">No</th>
                <th>Rak</th>
                <th>Baris</th>
                <th>kategori</th>
                <th widht="15%">Aksi</th>
              </tr>
        </thead>
        <tbody>
          @foreach ($raks as $item)
          <tr>
                <td>{{ $loop -> iteration }}</td>
                <td>{{ $item -> rak }}</td>
                <td>{{ $item -> baris }}</td>
                <td>{{ $item -> kategori->name }}</td>
                <td>
                    <div class="btn-group">
                    <span wire:click="Edit({{ $item->id }})" class="btn btn-sm btn-warning mr-2"><i class="fas fa-pen"></i>Edit</span>
                    <span wire:click="Delete({{ $item->id }})" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i>Hapus</span>
                </div>
          </td>
          </tr>
          @endforeach
        </tbody>
        </table>
        </div>
        <!-- /.card-body -->
        @endif
        </div>
      <!-- /.card -->

      <div class="row justify-content-center">
            {{ $raks->links() }}
      </div>

      @if ($raks->isEmpty())
          <div class="card">
            <div class="card-body">
              <div class="alert alert-warning">
                Data tidak tersedia
              </div>
            </div>
          </div>
      @endif

</div>
    
    