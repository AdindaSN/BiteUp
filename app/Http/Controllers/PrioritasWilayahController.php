namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataIndikator; // Sesuaikan dengan model yang menyimpan data wilayah & kerawanan

class PrioritasWilayahController extends Controller
{
    public function ringkasan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Query menghitung jumlah wilayah per kategori prioritas
        $sangatPrioritas = DataIndikator::where('tahun', $tahun)
            ->where('status_prioritas', 'Sangat Prioritas')
            ->count();

        $prioritas = DataIndikator::where('tahun', $tahun)
            ->where('status_prioritas', 'Prioritas')
            ->count();

        $cukup = DataIndikator::where('tahun', $tahun)
            ->where('status_prioritas', 'Cukup')
            ->count();

        $tidakPrioritas = DataIndikator::where('tahun', $tahun)
            ->where('status_prioritas', 'Tidak Prioritas')
            ->count();

        // Jika dipanggil via AJAX/API untuk endpoint ringkasan
        if ($request->wantsJson()) {
            return response()->json([
                'tahun' => $tahun,
                'sangat_prioritas' => $sangatPrioritas,
                'prioritas' => $prioritas,
                'cukup' => $cukup,
                'tidak_prioritas' => $tidakPrioritas,
            ]);
        }

        return view('prioritas-wilayah.index', compact(
            'sangatPrioritas', 
            'prioritas', 
            'cukup', 
            'tidak_prioritas', 
            'tahun'
        ));
    }
}