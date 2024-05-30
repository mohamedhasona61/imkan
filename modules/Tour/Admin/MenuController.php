<?php

namespace Modules\Tour\Admin;

use Illuminate\Http\Request;
use Modules\AdminController;
use Modules\Core\Models\Attributes;
use Modules\Core\Models\AttributesTranslation;
use Modules\Core\Models\MenusTranslation;
use Modules\Core\Models\ExtrasTranslation;
use Modules\Core\Models\MenuTour;
use Modules\Core\Models\Terms;
use Modules\Core\Models\TermsTranslation;
use Modules\Tour\Models\MenuExtras;

class MenuController extends AdminController
{
    protected $attributesClass;
    protected $extrasClass;
    protected $termsClass;
    public function __construct()
    {
        $this->setActiveMenu(route('tour.admin.index'));
        $this->attributesClass = MenuTour::class;
        $this->extrasClass = MenuExtras::class;
        $this->termsClass = Terms::class;
    }

    public function index(Request $request)
    {
        $listAttr = $this->attributesClass::where("service", 'tour');
        if (!empty($search = $request->query('s'))) {
            $listAttr->where('name', 'LIKE', '%' . $search . '%');
        }
        $listAttr->orderBy('created_at', 'desc');
        $data = [
            'rows'        => $listAttr->whereNotNull('parent_id')->get(),
            'row'         => new $this->attributesClass(),
            'translation'    => new MenusTranslation(),
            'breadcrumbs' => [
                [
                    'name' => __('Tour'),
                    'url'  => route('tour.admin.index')
                ],
                [
                    'name'  => __('Menu'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Tour::admin.menu.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $row = $this->attributesClass::find($id);
        if (empty($row)) {
            return redirect()->back()->with('error', __('Menu not found!'));
        }
        $translation = $row->translate($request->query('lang', get_main_lang()));
        $data = [
            'translation'    => $translation,
            'enable_multi_lang' => true,
            'rows'        => $this->attributesClass::where("service", 'tour')->get(),
            'row'         => $row,
            'breadcrumbs' => [
                [
                    'name' => __('Tour'),
                    'url'  => route('tour.admin.index')
                ],
                [
                    'name' => __('Menu'),
                    'url'  => route('tour.admin.menu.index')
                ],
                [
                    'name'  => __('Menu: :name', ['name' => $row->name]),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Tour::admin.menu.detail', $data);
    }

    public function store(Request $request)
    {
        $lang = "en";

        $this->validate($request, [
            'name' => 'required'
        ]);
        $id = $request->input('id');
        if ($id) {
            $row = $this->attributesClass::find($id);
            if (empty($row)) {
                return redirect()->back()->with('error', __('Menu not found!'));
            }
        } else {
            $row = new $this->attributesClass($request->input());
            $row->service = 'tour';
        }
if ($row->check_maximum === null) {
    $request->merge(['check_maximum' => 0]);
}
        $row->fill($request->input());
  
        $res = $row->saveOriginOrTranslation($request->input('lang'));
        if ($res) {
            return redirect()->back()->with('success', __('Attribute saved'));
        }
    }


    public function editAttrBulk(Request $request)
    {
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('Select at least 1 item!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Select an Action!'));
        }
        if ($action == "delete") {
            foreach ($ids as $id) {
                $query = $this->attributesClass::where("id", $id);
                $query->first();
                if (!empty($query)) {
                    $query->delete();
                }
            }
        }
        return redirect()->back()->with('success', __('Updated success!'));
    }

    public function terms(Request $request, $menu_id)
    {
        $row = $this->attributesClass::find($menu_id);
        if (empty($row)) {
            return redirect()->back()->with('error', __('Term not found!'));
        }
        $listTerms = $this->termsClass::where("menu_id", $menu_id);
        if (!empty($search = $request->query('s'))) {
            $listTerms->where('name', 'LIKE', '%' . $search . '%');
        }
        $listTerms->orderBy('created_at', 'desc');
        $data = [
            'rows'        => $listTerms->paginate(20),
            'menu'        => $row,
            "row"         => new $this->termsClass(),
            'translation'    => new TermsTranslation(),
            'breadcrumbs' => [
                [
                    'name' => __('Tour'),
                    'url'  => route('tour.admin.index')
                ],
                [
                    'name' => __('Menu'),
                    'url'  => route('tour.admin.menu.index')
                ],
                [
                    'name'  => __('Attribute: :name', ['name' => $row->name]),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Tour::admin.terms_menu.index', $data);
    }

    public function term_edit(Request $request, $id)
    {
        $row = $this->termsClass::find($id);
        if (empty($row)) {
            return redirect()->back()->with('error', __('Term not found'));
        }
        $translation = $row->translate($request->query('lang', get_main_lang()));
        $attr = $this->attributesClass::find($row->menu_id);
        $data = [
            'row'         => $row,
            'translation'    => $translation,
            'enable_multi_lang' => true,
            'breadcrumbs' => [
                [
                    'name' => __('Tour'),
                    'url'  => route('tour.admin.index')
                ],
                [
                    'name' => __('Menu'),
                    'url'  => route('tour.admin.menu.index')
                ],
                [
                    'name' => $attr->name,
                    'url'  => route('tour.admin.menu.term.index', ['menu_id' => $row->menu_id])
                ],
                [
                    'name'  => __('Term: :name', ['name' => $row->name]),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Tour::admin.terms_menu.detail', $data);
    }

    public function term_store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $id = $request->input('id');
        if ($id) {
            $row = $this->termsClass::find($id);
            if (empty($row)) {
                return redirect()->back()->with('error', __('Term not found!'));
            }
        } else {
            $row = new $this->termsClass($request->input());
            $row->menu_id = $request->input('menu_id');
        }

        $row->fill($request->input());
        

        $res = $row->saveOriginOrTranslation($request->input('lang'));
        if ($res) {
            return redirect()->back()->with('success', __('Term saved'));
        }
    }

    public function editTermBulk(Request $request)
    {
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('Select at least 1 item!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Select an Action!'));
        }
        if ($action == "delete") {
            foreach ($ids as $id) {
                $query = $this->termsClass::where("id", $id);
                $query->first();
                if (!empty($query)) {
                    $query->delete();
                }
            }
        }
        return redirect()->back()->with('success', __('Updated success!'));
    }
    
    
    public function index_extras(Request $request)
    {
        $listAttr = $this->extrasClass::where('menu_id', '!=', null);
        if (!empty($search = $request->query('s'))) {
            $listAttr->where('name', 'LIKE', '%' . $search . '%');
        }
        $listAttr->orderBy('created_at', 'desc');
        $data = [
            'rows'        => $listAttr->get(),
            'row'         => new $this->extrasClass(),
            'translation'    => new ExtrasTranslation(),
            'breadcrumbs' => [
                [
                    'name' => __('Extras'),
                    'url'  => route('tour.admin.extras.index')
                ],
                [
                    'name'  => __('Menu'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Tour::admin.extras.index', $data);
    }

    public function edit_extras(Request $request, $id)
    {
        $row = $this->extrasClass::find($id);
        if (empty($row)) {
            return redirect()->back()->with('error', __('Menu not found!'));
        }
        $translation = $row->translate($request->query('lang', get_main_lang()));
        $data = [
            'translation'    => $translation,
            'enable_multi_lang' => true,
            'rows'        => $this->extrasClass::where('menu_id', '!=', null)->get(),
            'row'         => $row,
            'breadcrumbs' => [
                [
                    'name' => __('Tour'),
                    'url'  => route('tour.admin.index')
                ],
                [
                    'name' => __('Menu'),
                    'url'  => route('tour.admin.menu.index')
                ],
                [
                    'name'  => __('Menu: :name', ['name' => $row->name]),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Tour::admin.extras.detail', $data);
    }

    public function store_extras(Request $request)
    {
        $lang = "en";

        $this->validate($request, [
            'name' => 'required'
        ]);
        $id = $request->input('id');



        if ($id) {
            $row = $this->extrasClass::find($id);
            if (empty($row)) {
                return redirect()->back()->with('error', __('Menu not found!'));
            }
        } else {
            $row = new $this->extrasClass($request->input());
        }


        $row->fill($request->input());
        $res = $row->saveOriginOrTranslation($request->input('lang'));
        if ($res) {
            $translation = ExtrasTranslation::where('origin_id', $id)->where('locale', $request->input("lang"))->first();
            if ($translation == null) {
                $translation  = new ExtrasTranslation();
                $translation->origin_id = $row->id;
                $translation->locale = $request->input("lang");
                $translation->name = $row->name;
                $translation->create_user = 1;
                $translation->save();
            }else{

                $translation->origin_id = $row->id;
                $translation->locale = $request->input("lang");
                $translation->name = $row->name;
                $translation->create_user = 1;
                $translation->save();
            }

            return redirect()->back()->with('success', __('Extras saved'));
        }
    }

}
