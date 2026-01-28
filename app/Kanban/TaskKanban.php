<?php

namespace App\Kanban;

use App\Invoice;
use App\Service;
use JinoAntony\Kanban\Kanban;
use JinoAntony\Kanban\KBoard;
use JinoAntony\Kanban\KItem;

class TaskKanban extends Kanban
{
    /**
     * Get the list of boards
     *
     * @return KBoard[]
     */
    public function getBoards()
    {
        return [
            KBoard::make('board1')
                ->setTitle('Quotation')
                ->canDragTo('board2'),

            KBoard::make('board2')
                ->setTitle('Jobcard')
                ->canDragTo('board3'),

            KBoard::make('board3')
                ->setTitle('Invoices')
                ->canDragTo('board2')
                ->canDragTo('board1'),
        ];
    }

    /**
     * Get the data for each board
     *
     * @return array
     */
    public function data()
    {
        // Fetch Quotations
        $quotations = Service::where([
            ['job_no', 'like', 'J%'],
            ['is_quotation', '=', 1],
            ['quotation_modify_status', '=', 1],
            ['soft_delete', '=', 0],
        ])->orderBy('id', 'DESC')->get();

        $color = 'grey';
        $board1 = [];
        foreach ($quotations as $quotation) {
            // Construct content with icons and new lines
            $content = '<b>'.getQuotationNumber($quotation->job_no).'</b><br>';
            $content .= '<i class="fas fa-tools"></i> '.$quotation->service_category.'<br>';
            $content .= '<i class="fas fa-user"></i> '.getCustomerName($quotation->customer_id).'<br>';
            $content .= '<i class="fas fa-car"></i> '.getVehicleName($quotation->vehicle_id).'<br>';
            $content .= '<div class="text-end"><b>'.getCurrencySymbols().''.number_format(getTotalPriceOfQuotation($quotation->id), 2).'</b></div>';

            if ($quotation->is_appove === 1) {
                $color = 'rgb(0, 128, 0)';
            } elseif ($quotation->is_appove === 0) {
                $color = 'rgb(255, 0, 0)';
            } elseif ($quotation->is_appove === null) {
                $color = 'rgb(255, 165, 0)';
            }

            // Add the colored left border
            $content = '<div style="border-left: 4px solid '.$color.'; padding: 10px;">'.$content.'</div>';

            $board1[] = KItem::make((string) $quotation->id)->setContent($content);
        }

        // Fetch Jobcards
        $jobcards = Service::where([
            ['soft_delete', 0],
            ['job_no', 'like', 'J%'],
        ])->whereNotIn('quotation_modify_status', [1])->orderBy('id', 'desc')->get();

        $board2 = [];
        foreach ($jobcards as $jobcard) {
            $content = '<b>'.$jobcard->job_no.'</b><br>';
            $content .= '<i class="fas fa-tools"></i> '.$jobcard->service_category.'<br>';
            $content .= '<i class="fas fa-user"></i> '.getCustomerName($jobcard->customer_id).'<br>';
            $content .= '<i class="fas fa-car"></i> '.getVehicleName($jobcard->vehicle_id).'<br>';
            $content .= '<div class="text-end"><b>'.getCurrencySymbols().''.number_format(getTotalPriceOfQuotation($jobcard->id), 2).'</b></div>';

            // Get the current date
            $currentDate = date('Y-m-d');

            // Check if service is open
            if ($jobcard->done_status == 0) {
                // Check if the service date is in the future
                if ($jobcard->service_date > $currentDate) {
                    $color = ' rgb(0, 0, 255)';
                } else {
                    $color = ' rgb(255, 0, 0)';
                }
            } elseif ($jobcard->done_status == 1) {
                $color = ' rgb(0, 128, 0)';
            } elseif ($jobcard->done_status == 2) {
                $color = ' rgb(255, 165, 0)';
            }

            // Add the colored left border
            $content = '<div style="border-left: 4px solid '.$color.'; padding: 10px;">'.$content.'</div>';

            $board2[] = KItem::make((string) $jobcard->id)->setContent($content);
        }

        // Fetch Invoices
        $invoices = Invoice::where([
            ['type', '!=', 2],
            ['soft_delete', 0],
        ])->orderBy('id', 'DESC')->get();

        $board3 = [];
        foreach ($invoices as $invoice) {

            $tbl_service = Service::where('id', '=', $invoice->sales_service_id)->first();

            // Construct content with icons and new lines
            $content = '<b>'.$invoice->invoice_number.'</b><br>';
            $content .= '<i class="fas fa-tools"></i> '.$tbl_service->service_category.'<br>';
            $content .= '<i class="fas fa-user"></i> '.getCustomerName($tbl_service->customer_id).'<br>';
            $content .= '<i class="fas fa-car"></i> '.getVehicleName($tbl_service->vehicle_id).'<br>';
            $content .= '<div class="text-end"><b>'.getCurrencySymbols().''.number_format(getTotalPriceOfQuotation($tbl_service->id), 2).'</b></div>';

            if ($invoice->payment_status == 0) {
                $color = ' rgb(255, 0, 0)';
            } elseif ($invoice->payment_status == 1) {
                $color = ' rgb(255, 165, 0)';
            } elseif ($invoice->payment_status == 2) {
                $color = ' rgb(0, 128, 0)';
            } else {
                $color = ' rgb(255, 0, 0)';
            }

            // Add the colored left border
            $content = '<div style="border-left: 4px solid '.$color.'; padding: 10px;">'.$content.'</div>';

            $board3[] = KItem::make((string) $invoice->id)->setContent($content);
        }

        return [
            'board1' => $board1,
            'board2' => $board2,
            'board3' => $board3,
        ];
    }

    /**
     * Build the kanban board styling
     *
     * @return $this
     */
    public function build()
    {
        return $this->element('.kanban-board')
            ->margin('20px')
            ->width('320px');
    }
}
