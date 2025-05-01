<?php

namespace App\Models;

use CodeIgniter\Model;

class PemeliharaanModel extends Model
{
    protected $table = 'pemeliharaan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['sarana_id', 'tgl_mulai', 'tgl_selesai', 'keterangan'];
    protected $useTimestamps = true;

    public function getWithSarana()
    {
        return $this->select('pemeliharaan.*, sarana.nama as nama_sarana, sarana.kategori')
            ->join('sarana', 'sarana.id = pemeliharaan.sarana_id');
    }

    public function getEventsForCalendar()
    {
        $events = $this->getWithSarana()->findAll();
        $formattedEvents = [];

        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event['nama_sarana'] . ' (' . $event['kategori'] . ')',
                'start' => $event['tgl_mulai'],
                'end' => date('Y-m-d', strtotime($event['tgl_selesai'] . ' +1 day')),
                'backgroundColor' => '#f39c12',
                'borderColor' => '#f39c12',
                'extendedProps' => [
                    'keterangan' => $event['keterangan']
                ]
            ];
        }

        return $formattedEvents;
    }
}
