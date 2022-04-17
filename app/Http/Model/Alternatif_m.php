<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Alternatif_m extends Model
{
	protected $table = 'tb_alternatif';
	protected $index_key = 'id';
	protected $index_key2 = 'kode_alternatif';
    public $timestamps  = false;

	public $rules;

    public function __construct()
	{
        $this->rules = [
            'insert' => [
                'kode_alternatif' => "required|unique:$this->table",
				'id_pengajuan' => "required|unique:$this->table",
            ],
			'update' => [
				'id_pengajuan' => 'required',
            ],
        ];
	}

    function get_all()
    {
		$query = DB::table("{$this->table} as a")
				->join('tb_pengajuan as b','a.id_pengajuan','=','b.id')
				->join('tb_nasabah as c','b.id_nasabah','=','c.id')
				->select(
					'a.*',
					'b.id as pengajuan_id',
					'b.id_pengajuan',
					'b.id_nasabah',
					'c.nama_nasabah',
					'c.alamat_nasabah',
					'c.telepon'
				);
				
		return $query->get();
    }

    function insert_data($data)
	{
		return self::insert($data);
	}

	function get_one($id)
	{
		$query = DB::table("{$this->table} as a")
				->join('tb_pengajuan as b','a.id_pengajuan','=','b.id')
				->join('tb_nasabah as c','b.id_nasabah','=','c.id')
				->select(
					'a.*',
					'b.id as pengajuan_id',
					'b.id_pengajuan',
					'b.id_nasabah',
					'c.nama_nasabah',
					'c.alamat_nasabah',
					'c.telepon'
				)
				->where("a.{$this->index_key}", $id);
				
		return $query->first();
	}

	function get_by( $where )
	{
		$query = DB::table("{$this->table} as a")
				->join('tb_pengajuan as b','a.id_pengajuan','=','b.id')
				->join('tb_nasabah as c','b.id_nasabah','=','c.id')
				->select(
					'a.*',
					'b.id as pengajuan_id',
					'b.id_pengajuan',
					'b.id_nasabah',
					'c.nama_nasabah',
					'c.alamat_nasabah',
					'c.telepon'
				)
				->where($where);
				
		return $query->first();
	}

	function get_by_in( $where, $data )
	{
		return self::whereIn($where, $data)->get();
	}

	function update_data($data, $id)
	{
		return self::where($this->index_key, $id)->update($data);
	}

	function update_by($data, Array $where)
	{
		$query = DB::table($this->table)->where($where);
		return $query->update($data);
	}

	function gen_code( $format )
	{
		$max_number = self::all()->max($this->index_key2);
		$noUrut = (int) substr($max_number, 6, 6);
		$noUrut++;
		$code = $format;
		$no_generate = $code . sprintf("%06s", $noUrut);

		return (string) $no_generate;
	}

}