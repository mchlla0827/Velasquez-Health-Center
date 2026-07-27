<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ForecastController extends Controller
{
    public function index()
    {

        // ==================================================
        // ROLE & USER CHECK
        // ==================================================

        $role = strtolower(session('admin_role') ?? Auth::user()->role ?? 'bhw');

        $userName = Auth::user()->name 
            ?? session('admin_name') 
            ?? session('user_name') 
            ?? ucfirst($role);



        // ==================================================
        // FORECAST ACCESS
        // ADMIN, NURSE, DOCTOR ONLY
        // ==================================================

        $canViewForecast = (
            $role === 'admin' ||
            $role === 'nurse' ||
            $role === 'doctor'
        );


        if (!$canViewForecast) {

            return redirect()
                ->route('bhw.inventory')
                ->with(
                    'error',
                    'Access Denied: You are not allowed to view Forecast.'
                );

        }



        // ADMIN + NURSE CAN MANAGE
        // DOCTOR VIEW ONLY

        $canManageForecast = (
            $role === 'admin' ||
            $role === 'nurse'
        );



        // ==================================================
        // FORECAST VARIABLES
        // ==================================================

        $medicines = Medicine::all();


        $shortageCount = 0;

        $highDemandCount = 0;

        $restockCount = 0;


        $forecastData = [];

        $forecastChart = [];


        $criticalItemGlobal = null;



        // ==================================================
        // MOVING AVERAGE FORECAST
        // ==================================================

        foreach ($medicines as $med) {


            $startDate = Carbon::now()->subMonths(3);

            $oneMonthAgo = Carbon::now()->subMonth();

            $twoMonthsAgo = Carbon::now()->subMonths(2);



            // LAST 3 MONTH DISPENSING RECORDS

            $records = $med->dispensingRecords()

                ->where(
                    'dispense_date',
                    '>=',
                    $startDate
                )

                ->get();



            // MONTHLY CONSUMPTION

            $period1 = $records

                ->where(
                    'dispense_date',
                    '>=',
                    $oneMonthAgo
                )

                ->sum('quantity_dispensed');



            $period2 = $records

                ->where(
                    'dispense_date',
                    '>=',
                    $twoMonthsAgo
                )

                ->where(
                    'dispense_date',
                    '<',
                    $oneMonthAgo
                )

                ->sum('quantity_dispensed');



            $period3 = $records

                ->where(
                    'dispense_date',
                    '>=',
                    $startDate
                )

                ->where(
                    'dispense_date',
                    '<',
                    $twoMonthsAgo
                )

                ->sum('quantity_dispensed');





            // ==================================================
            // MOVING AVERAGE FORMULA
            // ==================================================

            $totalConsumption =
                $period1 +
                $period2 +
                $period3;



            $numberOfPeriods = count(
                array_filter([
                    $period1,
                    $period2,
                    $period3
                ])
            );



            if ($numberOfPeriods > 0) {


                $estDemand = (int) round(
                    $totalConsumption /
                    $numberOfPeriods
                );


            } else {


                $estDemand =
                    ($med->total_stock < 50)
                    ? 50
                    : 0;

            }





            // ==================================================
            // CHART DATA
            // ==================================================

            $forecastChart[] = [

                'medicine' => $med->name,


                'historical' => [

                    $period3,

                    $period2,

                    $period1

                ],


                'forecast' => $estDemand

            ];





            // ==================================================
            // INVENTORY STATUS
            // ==================================================

            $currentStock = (int)$med->total_stock;



            $status = 'Stable Inventory';

            $statusClass = 'text-green-600';

            $recommendation = 'Maintain Current Stock';


            $criticalItem = null;





            // SHORTAGE CHECK

            if(
                $estDemand > 0 &&
                $currentStock < $estDemand
            ){


                $status =
                    'Possible Shortage';



                $statusClass =
                    'text-red-600 font-bold';



                $suggestedQty =
                    (($estDemand * 2)
                    - $currentStock);



                $recommendation =
                    'Restock Immediately | Suggested: '
                    .$suggestedQty;



                $shortageCount++;


                $restockCount++;


                $criticalItem = $med;


                $criticalItemGlobal = $med;


            }


            elseif(
                $currentStock < 50
            ){


                $status =
                    'Low Stock Alert';


                $statusClass =
                    'text-yellow-600';


                $recommendation =
                    'Monitor Inventory';



                $highDemandCount++;


                $restockCount++;


            }







            // ==================================================
            // TABLE DATA
            // ==================================================

            $forecastData[] = [

                'medicine' => $med,


                'current_stock' => $currentStock,


                'est_demand' => $estDemand,


                'status' => $status,


                'status_class' => $statusClass,


                'recommendation' => $recommendation,


                'critical_item' => $criticalItem

            ];


        }





        // ==================================================
        // AI RECOMMENDATION
        // ==================================================

        $aiTitle = '';

        $aiMessage = '';

        $aiAction = '';

        $aiStatus = 'success';



        if($shortageCount > 0){


            $medicineName =
                $criticalItemGlobal
                ? $criticalItemGlobal->name
                : 'one or more medicines';



            $aiTitle =
                'Potential Stock Shortage Detected';



            $aiMessage =
                "The Moving Average Forecast predicts that {$medicineName} may fall below the required stock level within the next 30 days.";



            $aiAction =
                "Review recommended restock quantities and prioritize replenishment.";



            $aiStatus =
                'warning';



        }


        elseif($highDemandCount > 0){


            $aiTitle =
                'Low Stock Alert';



            $aiMessage =
                "Some medicines are approaching the minimum inventory threshold based on current stock levels.";



            $aiAction =
                "Continue monitoring inventory and prepare replenishment if demand increases.";



            $aiStatus =
                'warning';



        }


        else{


            $aiTitle =
                'Inventory Status is Healthy';



            $aiMessage =
                "Based on the Moving Average Forecast, no medicine is expected to experience stock shortage within the next 30 days.";



            $aiAction =
                "Maintain current inventory levels and continue routine monitoring.";



            $aiStatus =
                'success';


        }





        // ==================================================
        // RETURN VIEWS
        // ==================================================


        if($role === 'admin'){


            return view(
                'admin.forecast',
                compact(
                    'role',
                    'userName',
                    'canManageForecast',
                    'forecastData',
                    'forecastChart',
                    'shortageCount',
                    'highDemandCount',
                    'restockCount',
                    'criticalItemGlobal',
                    'aiTitle',
                    'aiMessage',
                    'aiAction',
                    'aiStatus'
                )
            );

        }




        elseif($role === 'nurse'){


            return view(
                'nurse.forecast',
                compact(
                    'role',
                    'userName',
                    'canManageForecast',
                    'forecastData',
                    'forecastChart',
                    'shortageCount',
                    'highDemandCount',
                    'restockCount',
                    'criticalItemGlobal',
                    'aiTitle',
                    'aiMessage',
                    'aiAction',
                    'aiStatus'
                )
            );

        }




        elseif($role === 'doctor'){


            return view(
                'admin.forecast',
                compact(
                    'role',
                    'userName',
                    'canManageForecast',
                    'forecastData',
                    'forecastChart',
                    'shortageCount',
                    'highDemandCount',
                    'restockCount',
                    'criticalItemGlobal',
                    'aiTitle',
                    'aiMessage',
                    'aiAction',
                    'aiStatus'
                )
            );

        }

    }
}