<?php

namespace App\Livewire\Petugas;

use App\Models\Peminjaman;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Models\User as Modelsuser;



class Transaksi extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $belum_dipinjam, $sedang_dipinjam, $selesai_dipinjam, $search ,$datauser;   
    public $tanggal_pinjam;     

    public function belumDipinjam()
    {
        $this->format();
        $this->belum_dipinjam = true;
    }
    public function sedangDipinjam()
    {
        $this->format();
        $this->sedang_dipinjam = true;
    }
    public function selesaiDipinjam()
    {
        $this->format();
        $this->selesai_dipinjam = true;
    }

    public function pinjam(Peminjaman $peminjaman)
    {
       
        // kurangi stok buku setelah pinjama
        foreach ($peminjaman->detail_peminjaman as $detail_peminjaman) {

            $detail_peminjaman->buku->update([
                'stok' => $detail_peminjaman->buku->stok -1
            ]);
        }

        $this->tanggal_pinjam = $peminjaman->tanggal_pinjam;
        $peminjaman->update([
            'petugas_pinjam' =>auth()->user()->id,
            'tanggal_pinjam' => today(),
            'tanggal_kembali' => Carbon::create($this->tanggal_pinjam)->addDays(10),
            'status' => 2
        ]);

        session()->flash('Sukses', 'Buku berhasil dipinjamakan.');
    }

    //Kembali
    public function kembali(Peminjaman $peminjaman)
    {
        $data = [
            'status' => 3,
            'petugas_kembali' => auth()->user()->id,
            'tanggal_pengembalian' => today(),
            'denda' =>0
        ];

        // tambah stok buku setelah kembali
        foreach ($peminjaman->detail_peminjaman as $detail_peminjaman) {
            $detail_peminjaman->buku->update([
                'stok' => $detail_peminjaman->buku->stok +1
            ]);
        }

        // denda
        if (Carbon::create($peminjaman->tanggal_kembali)->lessThan(today())) {
            $denda = Carbon::create($peminjaman->tanggal_kembali)->diffInDays(today());
        //   dd($denda);
            $denda *= 1000;
            $data['denda'] = $denda;    
        }

        $peminjaman->update($data);
        session()->flash('Sukses', 'Buku berhasil dikembalikan.');        
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {

        if ($this->search) {

            if ($this->belum_dipinjam) {
                $transaksi  = Peminjaman::latest()->where('kode_pinjam', 'LIKE', '%' . $this->search . '%')->where('status', 1)->paginate(5);
            } 
    
            elseif($this->sedang_dipinjam)
            {
                $transaksi  = Peminjaman::latest()->where('kode_pinjam', 'LIKE', '%' . $this->search . '%')->where('status', 2)->paginate(5);
            }
            elseif($this->selesai_dipinjam)
            {
                $transaksi  = Peminjaman::latest()->where('kode_pinjam', 'LIKE', '%' . $this->search . '%')->where('status', 3)->paginate(5);
            }
            else {
                $transaksi  = Peminjaman::latest()->where('kode_pinjam', 'LIKE', '%' . $this->search . '%')->where('status','!=',0)->paginate(5);
            }

        } else {
            if ($this->belum_dipinjam) {
                $transaksi  = Peminjaman::latest()->where('status', 1)->paginate(5);
            } 
    
            elseif($this->sedang_dipinjam)
            {
                $transaksi  = Peminjaman::latest()->where('status', 2)->paginate(5);
            }
            elseif($this->selesai_dipinjam)
            {
                $transaksi  = Peminjaman::latest()->where('status', 3)->paginate(5);
            }
            else {
                $transaksi  = Peminjaman::latest()->where('status','!=',0)->paginate(5);
            }
        }
       
        return view('livewire.petugas.transaksi',[
            'transaksi'  => $transaksi
        ]);
    }

    public function format()
    {
        $this->belum_dipinjam = false;
        $this->sedang_dipinjam = false;
        $this->selesai_dipinjam = false;
    }
}
