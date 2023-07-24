
class ImageController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 5; // Number of items to load per request
        $images = Image::paginate($perPage);
        return response()->json($images);
    }
    public function uploadImage(Request $request)
    {
        
        $video = $request->file('image');
        $caption = $request->file('caption');
        
        $imagePath = $request->file('image')->store('images');
        $path = public_path('image');
        
        $filename = $video->getClientOriginalName();

        $video->move($path, $filename);
        
        $image = new Image();
        $image->image = $filename;
        $image->caption = $request->input('caption', '');
        $image->save();

        return redirect()->back()->with('success', 'Image uploaded successfully!');
    }
    public function loadMoreData(Request $request)
    {
        // Implement logic to fetch additional data
        $offset = $request->input('offset', 5);
        $limit = 1; // Number of data entries to load per request

        $data = Image::skip($offset)->take($limit)->get();

        return response()->json($data);
    }
}
