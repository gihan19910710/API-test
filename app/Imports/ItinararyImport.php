<?php

namespace App\Imports;

use App\Models\Itinerary;
use App\Models\ItinerarycheckList;
use App\Models\ItineraryList;
use App\Models\Outlet;
use App\Models\OutletType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ItinararyImport implements WithMultipleSheets
{
    protected $itineraryLists = [];
    protected $checklistData = [];

    public function sheets(): array
    {
        return [
            0 => new ItinerarySheetImport($this->itineraryLists),
            1 => new ChecklistSheetImport($this->itineraryLists, $this->checklistData),
        ];
    }
}

class ItinerarySheetImport implements ToCollection, WithHeadingRow
{
    protected $itineraryLists;

    public function __construct(&$itineraryLists)
    {
        $this->itineraryLists = &$itineraryLists;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Try to find the outlet_type by its name from the row, return null if not found
            $outletType = OutletType::where('outlet_name', $row['outlet_type'])->first();
            $outletTypeId = $outletType ? $outletType->id : null;

            // Try to find the outlet by its name from the row, return null if not found
            $outlet = Outlet::where('outlet_name', $row['outlet'])->first();
            $outletId = $outlet ? $outlet->id : null;

            // Create the Itinerary
            $itinerary = Itinerary::create([
                'title' => $row['title'],
                'province' => $row['province'],
                'district' => $row['district'],
                'dsd' => $row['dsd'],
                'gnd' => $row['gnd'],
                'description' => $row['description'],
                'outlet_type' => $row['outlet_type'],
                'outlet' => $row['outlet'],
                'outlet_type_id' => $outletTypeId,
                'outlet_id' => $outletId,
            ]);

            // Split comma-separated values for institutes and their details
            $institutes = explode(',', $row['institute_names']);
            $lats = explode(',', $row['lat']);
            $lngs = explode(',', $row['lng']);
            $addresses = explode(',', $row['address']);

            // Create multiple ItineraryList entries for each set of comma-separated values
            for ($i = 0; $i < count($institutes); $i++) {
                $itineraryList = ItineraryList::create([
                    'itinarary_id' => $itinerary->id,
                    'institute_name' => $institutes[$i] ?? null,
                    'lat' => $lats[$i] ?? null,
                    'lng' => $lngs[$i] ?? null,
                    'address' => $addresses[$i] ?? null,
                ]);

                // Store the created ItineraryList in the array
                $this->itineraryLists[] = $itineraryList;
            }
        }
    }


}

class ChecklistSheetImport implements ToCollection, WithHeadingRow
{
    protected $itineraryLists;
    protected $checklistData;

    public function __construct(&$itineraryLists, &$checklistData)
    {
        $this->itineraryLists = &$itineraryLists;
        $this->checklistData = &$checklistData;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Split comma-separated values for checklist data
            $mainCategories = explode(',', $row['main_category']);
            $checklistItems = explode(',', $row['checklist_items']);
            $instituteName = trim($row['institute_names']); // Get the institute name

            // Store checklist data for later association
            $this->checklistData[] = [
                'main_categories' => $mainCategories,
                'checklist_items' => $checklistItems,
                'institute_name' => $instituteName, // Store institute name for matching
            ];
        }

        // Associate checklist items with ItineraryLists by matching institute names
        foreach ($this->itineraryLists as $itineraryList) {
            foreach ($this->checklistData as $checklistRow) {
                // Find the matching ItineraryList using the institute name
                if ($itineraryList->institute_name === $checklistRow['institute_name']) {
                    $mainCategories = $checklistRow['main_categories'];
                    $checklistItems = $checklistRow['checklist_items'];

                    // Iterate through each category and assign corresponding checklist items
                    foreach ($mainCategories as $index => $category) {
                        // Iterate through all checklist items for this category
                        foreach ($checklistItems as $checkItem) {
                            ItinerarycheckList::create([
                                'itinerary_list_id' => $itineraryList->id, // Use matched itinerary_list_id
                                'main_category' => $category,
                                'item_name' => $checkItem,
                                'checked' => 0, // Default unchecked
                            ]);
                        }
                    }
                }
            }
        }
    }
}



