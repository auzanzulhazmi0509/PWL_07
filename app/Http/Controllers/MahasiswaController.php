<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //fungsi eloquent menampilkan data menggunakan pagination
        // $mahasiswas = Mahasiswa::all(); // Mengambil semua isi tabel
        // $posts = Mahasiswa::orderBy('Nim', 'desc')->paginate(6);
        // return view('mahasiswas.index', compact('mahasiswas'));
        // with('i', (request()->input('page', 1) - 1) * 5);
        // $mahasiswas1 = DB::table('mahasiswas')->simplePaginate(5);	
        $mahasiswas = Mahasiswa::where([
            ['Nama','!=',Null],
            [function($query)use($request){
                if (($term = $request->term)) {
                    $query->orWhere('Nama','LIKE','%'.$term.'%')->get();
                }
            }]
        ])
        ->orderBy('Nim','desc')
        // ->paginate(5);
        ->simplePaginate(5);


        return view('mahasiswas.index' , compact('mahasiswas'))
        ->with('i',(request()->input('page',1)-1)*5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mahasiswas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
            'Email' => 'required',
            'Tanggal_lahir' => 'required',
            ]);
            //fungsi eloquent untuk menambah data
            Mahasiswa::create($request->all());

            //jika data berhasil ditambahkan, akan kembali ke halaman utama
            return redirect()->route('mahasiswas.index')
                ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($Nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        $Mahasiswa = Mahasiswa::find($Nim);
        return view('mahasiswas.detail', compact('Mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    public function edit($Nim)
    {
                //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
                $Mahasiswa = Mahasiswa::find($Nim);
                return view('mahasiswas.edit', compact('Mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $Nim)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
            'Email' => 'required',
            'Tanggal_lahir' => 'required',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy( $Nim)
   {
       //fungsi eloquent untuk menghapus data
       Mahasiswa::find($Nim)->delete();
       return redirect()->route('mahasiswas.index')
            -> with('success', 'Mahasiswa Berhasil Dihapus');
   }
}