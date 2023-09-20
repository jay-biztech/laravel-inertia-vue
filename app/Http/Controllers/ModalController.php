use Illuminate\Http\Request;
use Inertia\Inertia;

class ModalController extends Controller
{
    public function show(Request $request)
    {
        // Your logic to show the modal

        return Inertia::render('Welcome', [
            // Additional data you want to pass to the view
        ]);
    }

    public function hide(Request $request)
    {
        // Your logic to hide the modal

        return Inertia::render('Welcome', [
            // Additional data you want to pass to the view
        ]);
    }
}