<?php
namespace App\Http\Controllers\Admin;
use App\Dish;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
            if (!empty($data["search1"])) {
                $dishes=Dish::where('user_id', Auth::id())->where("type", "like", '%'.$data["search1"].'%')->get();
            }
            if (!empty($data['search2'])) {
                $dishes=Dish::where('user_id', Auth::id())->where("gluten", "1")->get();
            }
            if (!empty($data['search3'])) {
                $dishes=Dish::where('user_id', Auth::id())->where("vegan", "0")->get();
            }
            if (!empty($data['search4'])) {
                $dishes=Dish::where('user_id', Auth::id())->orderBy('price','asc')->get();
            }
            if(empty($data["search1"]) && empty($data['search2']) && empty($data["search3"]) && empty($data['search4'])){
            $dishes=Dish::where('user_id',Auth::id())->get();
            }

        return view('user.dishes.index', compact('dishes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('user.dishes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $data=$request->all();
        $data['user_id']=Auth::id();
        $dish= new Dish();
        $dish->fill($data);
        $dish->img = $request->file('image')->store('images');
        $dish->save();
        return redirect()->route('dishes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Dish $dish)
    {
        return view('user.dishes.show',compact('dish'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Dish $dish)
    {
     return view('user.dishes.edit',compact('dish'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dish $dish)
    {
        $data= $request->all();
        $dish->img = $request->file('image')->store('images');
        $dish->update($data);
        return redirect()->route('dishes.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dish $dish)
    {
        //  $dish->user()->dissociate();
        $dish->delete();
        return redirect()->route('dishes.index');
    }
}
