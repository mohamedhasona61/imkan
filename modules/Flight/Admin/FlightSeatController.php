<?php


    namespace Modules\Flight\Admin;


    use Auth;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Validation\Rule;
    use Modules\AdminController;
    use Modules\Flight\Models\Flight;
    use Modules\Flight\Models\FlightSeat;

    class FlightSeatController extends AdminController
    {

        /**
         * @var string
         */
        private $flight_seat;
        /**
         * @var string
         */
        private $flight;
        /**
         * @var string
         */
        private $currentFlight;

        public function __construct()
        {
            $this->setActiveMenu(route('flight.admin.index'));
            $this->flight_seat = FlightSeat::class;
            $this->flight = Flight::class;
            $this->currentFlight = Flight::class;
        }

        public function callAction($method, $parameters)
        {
            if(!Flight::isEnable())
            {
                return redirect('/');
            }
            return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
        }

        protected function hasFlightPermission($flight_id = false){
            if(empty($flight_id)) return false;
            $flight = $this->flight::find($flight_id);
            if(empty($flight)) return false;
            if(!$this->hasPermission('flight_update') and $flight->author_id != Auth::id()){
                return false;
            }
            $this->currentFlight = $flight;
            return true;
        }

        public function index(Request $request,$flight_id)
        {
            $this->hasFlightPermission($flight_id);
            $this->checkPermission('flight_view');
            $query = $this->currentFlight->flightSeat()->with(['flight','seatType']) ;
            $query->orderBy('id', 'desc');

            if ($this->hasPermission('flight_manage_others')) {
                if (!empty($author = $request->input('vendor_id'))) {
                    $query->where('author_id', $author);
                }
            } else {
                $query->where('author_id', Auth::id());
            }
            $data = [
                'currentFlight'=>$this->currentFlight,
                'row'=>new $this->flight_seat,
                'rows'               => $query->with(['author'])->paginate(20),
                'flight_manage_others' => $this->hasPermission('flight_manage_others'),
                'breadcrumbs'        => [
                    [
                        'name'=> __("Flight: :name :code #:id",['name'=>$this->currentFlight->title,'code'=>$this->currentFlight->code,'id'=>$this->currentFlight->id]),
                        'url'  => route('flight.admin.edit',$this->currentFlight)
                    ],
                    [
                        'name' => __('Flight seat'),
                        'url'  => route('flight.admin.flight.seat.index',['flight_id'=>$this->currentFlight->id])
                    ],
                    [
                        'name'  => __('All'),
                        'class' => 'active'
                    ],
                ],
                'page_title'=>__("Flight seat Management")
            ];
            return view('Flight::admin.flight.seat.index', $data);
        }
        public function edit(Request $request,$flight_id, $id)
        {
            $this->hasFlightPermission($flight_id);
            $this->checkPermission('flight_update');
            $row = $this->flight_seat::find($id);
            if (empty($row)) {
                return redirect(route('flight.admin.flight.seat.index'));
            }
            if (!$this->hasPermission('flight_manage_others')) {
                if ($row->author_id != Auth::id()) {
                    return redirect(route('flight.admin.index'));
                }
            }
            $data = [
                'currentFlight'=>$this->currentFlight,
                'row'            => $row,
                'breadcrumbs'    => [
                    [
                        'name'=> __("Flight: :name :code #:id",['name'=>$this->currentFlight->title,'code'=>$this->currentFlight->code,'id'=>$this->currentFlight->id]),
                        'url'  => route('flight.admin.edit',$this->currentFlight)
                    ],
                    [
                        'name' => __('Flight seat'),
                        'url'  => route('flight.admin.flight.seat.index',['flight_id'=>$this->currentFlight->id])
                    ],
                    [
                        'name'  => __('Edit flight_seat'),
                        'class' => 'active'
                    ],
                ],
                'page_title'=>__("Edit: :name",['name'=>$row->code])
            ];
            return view('Flight::admin.flight.seat.detail', $data);
        }

        public function store( Request $request,$flight_id, $id ){

            $this->hasFlightPermission($flight_id);
            if($id>0){
                $this->checkPermission('flight_update');
                $row = $this->flight_seat::find($id);
                if (empty($row)) {
                    return redirect(route('flight.admin.flight.seat.index',['flight_id'=>$this->currentFlight->id]));
                }

                if($row->author_id != Auth::id() and !$this->hasPermission('flight_manage_others'))
                {
                    return redirect(route('flight.admin.flight.seat.index',['flight_id'=>$this->currentFlight->id]));
                }
            }else{
                $this->checkPermission('flight_create');
                $row = new $this->flight_seat();
            }
            $validator = Validator::make($request->all(), [
                'seat_type'=>[
                    'required',
                    Rule::unique(FlightSeat::getTableName())->where(function ($query)use($flight_id){
                        return $query->where('flight_id',$flight_id);
                    })->ignore($row)
                ],
                'price'=>'required',
                'max_passengers'=>'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with(['errors' => $validator->errors()]);
            }
            $dataKeys = [
                'seat_type','price','max_passengers','person','baggage_check_in','baggage_cabin'
            ];

            if($this->hasPermission('flight_manage_others')){
                $dataKeys[] = 'author_id';
            }
            $row->fillByAttr($dataKeys,$request->input());
            $row->flight_id = $this->currentFlight->id;
            $res = $row->save();
            if ($res) {
                return redirect(route('flight.admin.flight.seat.edit',['flight_id'=>$flight_id,'id'=>$row->id]))->with('success', __('Flight seat saved') );
            }
        }
        public function bulkEdit(Request $request)
        {

            $ids = $request->input('ids');
            $action = $request->input('action');
            if (empty($ids) or !is_array($ids)) {
                return redirect()->back()->with('error', __('No items selected!'));
            }
            if (empty($action)) {
                return redirect()->back()->with('error', __('Please select an action!'));
            }

            switch ($action){
                case "delete":
                    foreach ($ids as $id) {
                        $query = $this->flight_seat::where("id", $id);
                        if (!$this->hasPermission('flight_manage_others')) {
                            $query->where("create_user", Auth::id());
                            $this->checkPermission('flight_delete');
                        }
                        $row  =  $query->first();
                        if(!empty($row)){
                            $row->delete();
                        }
                    }
                    return redirect()->back()->with('success', __('Deleted success!'));
                    break;
                case "permanently_delete":
                    foreach ($ids as $id) {
                        $query = $this->flight_seat::where("id", $id);
                        if (!$this->hasPermission('flight_manage_others')) {
                            $query->where("create_user", Auth::id());
                            $this->checkPermission('flight_delete');
                        }
                        $row  =  $query->first();
                        if($row){
                            $row->delete();
                        }
                    }
                    return redirect()->back()->with('success', __('Permanently delete success!'));
                    break;
                case "clone":
                    $this->checkPermission('flight_create');
                    foreach ($ids as $id) {
                        (new $this->flight_seat())->saveCloneByID($id);
                    }
                    return redirect()->back()->with('success', __('Clone success!'));
                    break;
                default:
                    // Change status
                    foreach ($ids as $id) {
                        $query = $this->flight_seat::where("id", $id);
                        if (!$this->hasPermission('flight_manage_others')) {
                            $query->where("create_user", Auth::id());
                            $this->checkPermission('flight_update');
                        }
                        $row = $query->first();
                        $row->status  = $action;
                        $row->save();
                    }
                    return redirect()->back()->with('success', __('Update success!'));
                    break;
            }


        }
        public function getForSelect2(Request $request)
        {
            $pre_selected = $request->query('pre_selected');
            $selected = $request->query('selected');

            if($pre_selected && $selected){
                $item = $this->flight_seat::find($selected);
                if(empty($item)){
                    return response()->json([
                        'text'=>''
                    ]);
                }else{
                    return response()->json([
                        'text'=>$item->name
                    ]);
                }
            }
            $q = $request->query('q');
            $query = $this->flight_seat::select('id', 'name as text');
            if ($q) {
                $query->where('name', 'like', '%' . $q . '%');
            }
            $res = $query->orderBy('id', 'desc')->limit(20)->get();
            return response()->json([
                'results' => $res
            ]);
        }

    }