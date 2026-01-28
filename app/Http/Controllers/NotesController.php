<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Notes;
use App\Product;
use App\Purchase;
use App\Service;
use App\Setting;
use App\User;
use App\Vehicle;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list()
    {
        $notes = Notes::get();

        foreach ($notes as $note) {
            $not = Notes::find($note->id);

            if ($not) {
                $entityType = class_basename($note->entity_type);
                $entityId = $note->entity_id;

                switch ($entityType) {
                    case 'User':
                        $entity = User::find($entityId);
                        $entityLabel = 'Name';
                        if ($entity->role == 'Supplier') {
                            $note->entity_type = 'Supplier';
                        } else {
                            $note->entity_type = 'Customer';
                        }
                        break;
                        // case 'Service':
                        //     $entity = Service::find($entityId);
                        //     dd($entity);
                        //     if ($entity->is_quotation == 1) {
                        //         $note->entity_type = 'Quotation';
                        //     } else {
                        //         $note->entity_type = 'Jobcard';
                        //     }
                        //     break;
                    case 'Service':
                        $entity = Service::find($entityId);
                        if ($entity && isset($entity->is_quotation)) { // Ensure entity and property exist
                            if ($entity->is_quotation == 1) {
                                $note->entity_type = 'Quotation';
                            } else {
                                $note->entity_type = 'Jobcard';
                            }
                        }
                        break;

                    default:
                        $note->entity_type = $entityType;
                        break;
                }
            }
        }

        return view('notes.list', compact('notes'));
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');
        if (! empty($ids)) {
            Notes::whereIn('id', $ids)->delete(); // Use delete() instead of destroy()
        }

        return redirect('/notes/list')->with('message', 'Notes Deleted Successfully');
    }

    public function view(Request $request)
    {
        $id = $request->id;
        $note = Notes::find($id);
        $entityDetails = null;
        $entityLabel = 'Name';
        $noteFor = null;
        $goModuleUrl = null;
        $goModuleText = null;

        if ($note) {
            $entityType = class_basename($note->entity_type);
            $entityId = $note->entity_id;

            switch ($entityType) {
                case 'User':
                    $entity = User::find($entityId);
                    $entityDetails = $entity ? $entity->name.' '.$entity->lastname : null;
                    $entityLabel = 'Name';
                    if ($entity->role == 'Supplier') {
                        $noteFor = 'Supplier';
                        $goModuleUrl = url('/supplier/list/'.$entityId);
                        $goModuleText = 'View Supplier';
                    } else {
                        $noteFor = 'Customer';
                        $goModuleUrl = url('/customer/list/'.$entityId);
                        $goModuleText = 'View Customer';
                    }
                    break;
                case 'Vehicle':
                    $entity = Vehicle::find($entityId);
                    $entityDetails = $entity ? getVehicleName($entityId) : null;
                    $entityLabel = 'Vehicle Name';
                    $noteFor = 'Vehicle';
                    $goModuleUrl = url('/vehicle/list/view/'.$entityId);
                    $goModuleText = 'View Vehicle';
                    break;
                case 'Product':
                    $entity = Product::find($entityId);
                    $entityDetails = $entity ? $entity->product_no : null;
                    $entityLabel = 'Product Number';
                    $noteFor = 'Product';
                    $goModuleUrl = url('product/modalprint').'?product_id='.$entityId;
                    $goModuleText = 'View Product';
                    break;
                case 'Purchase':
                    $entity = Purchase::find($entityId);
                    $entityDetails = $entity ? $entity->purchase_no : null;
                    $entityLabel = 'Purchase Code';
                    $noteFor = 'Purchase';
                    $goModuleUrl = url('/purchase/list');
                    $goModuleText = 'Purchase List';
                    break;
                case 'Service':
                    $entity = Service::find($entityId);
                    if ($entity->is_quotation == 1) {
                        $entityDetails = $entity ? getQuotationNumber($entity->job_no) : null;
                        $entityLabel = 'Quotation No.';
                        $noteFor = 'Quotation';
                        $goModuleUrl = url('/quotation/list/print').'?servicesid='.$entityId;
                        $goModuleText = 'View Quotation';
                    } else {
                        $entityDetails = $entity ? $entity->job_no : null;
                        $entityLabel = 'Jobcard No.';
                        $noteFor = 'Jobcard';
                        $goModuleUrl = url('/service/list/view/'.$entityId);
                        $goModuleText = 'View Jobcard';
                    }
                    break;
                case 'Invoice':
                    $entity = Invoice::find($entityId);
                    $entityDetails = $entity ? $entity->invoice_number : null;
                    $entityLabel = 'Invoice Number';
                    $noteFor = 'Invoice';
                    $goModuleUrl = url('/invoice/list/');
                    $goModuleText = 'Invoice List';
                    break;
                default:
                    $entityDetails = null;
                    $entityLabel = 'Details';
                    break;
            }
        }

        $setting = Setting::first();

        $html = view('notes.viewnote', compact('note', 'setting', 'noteFor', 'entityDetails', 'entityLabel', 'goModuleUrl', 'goModuleText'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }
}
