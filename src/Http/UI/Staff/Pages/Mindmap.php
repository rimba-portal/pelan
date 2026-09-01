<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;

class Mindmap extends Page
{
    // The visual icon in the Filament sidebar
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-share';

    // The heading displayed at the top of your page
    protected static ?string $title = 'Project Mindmap';

    // The Blade view layout file
    protected string $view = 'bites::mermaid-md';

    public string $mermaid;

    public function mount(): void
    {
        // 1. Raw JSON Dataset Input
        $json = '{
            "locations": [
                {"id":1,"name":"Amkor","code":"ATM","type":"Enterprise","parent_id":null,"org_corp_id":1,"description":"The company"},
                {"id":2,"name":"M1 Site","code":"M1","type":"Site","parent_id":null,"org_corp_id":1,"description":"Site with address in the Enterprise"},
                {"id":3,"name":"Office Building Ground Level","code":"OfficeGrd","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the Office Building Ground Level"},
                {"id":4,"name":"Office Building 1st Level","code":"Office1st","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the Office Building 1st Level"},
                {"id":5,"name":"S1 Building Ground P1","code":"S1P1","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S1 Building Ground P1"},
                {"id":6,"name":"S1 Building Ground P2","code":"S1P2","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S1 Building Ground P2"},
                {"id":7,"name":"S1 Building Ground P3","code":"S1P3","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S1 Building Ground P3"},
                {"id":8,"name":"S1 Building Ground P4","code":"S1P4","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S1 Building Ground P4"},
                {"id":9,"name":"S3 Building Ground P1","code":"S3P1","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S3 Building Ground P1"},
                {"id":10,"name":"S3 Building Ground P2","code":"S3P2","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S3 Building Ground P2"},
                {"id":11,"name":"S3 Building Ground P3","code":"S3P3","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S3 Building Ground P3"},
                {"id":12,"name":"Cafeteria","code":"CAFE","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the Cafeteria"},
                {"id":13,"name":"Central Locker","code":"SCL1","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the Central Locker"},
                {"id":14,"name":"S4 Building Ground P1","code":"S4P1","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S4 Building Ground P1"},
                {"id":15,"name":"S4 Building Ground P2","code":"S4P2","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S4 Building Ground P2"},
                {"id":16,"name":"S4 Building Ground P3","code":"S4P3","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S4 Building Ground P3"},
                {"id":17,"name":"S4 Building 1st Level P4","code":"S4P4","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S4 Building 1st Level P4"},
                {"id":18,"name":"S4 Building 1st Level P5","code":"S4P5","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S4 Building 1st Level P5"},
                {"id":19,"name":"S4 Building 1st Level P6","code":"S4P6","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the S4 Building 1st Level P6"},
                {"id":20,"name":"Warehouse","code":"WH","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the Warehouse"},
                {"id":21,"name":"Powerhouse","code":"PH","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the Powerhouse"},
                {"id":22,"name":"Plating Building","code":"PB","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the Plating Building"},
                {"id":23,"name":"Staff Carpark 1","code":"SCP1","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the Staff Carpark 1"},
                {"id":24,"name":"Staff Carpark 2","code":"SCP2","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the Staff Carpark 2"},
                {"id":25,"name":"Staff Bike Park 1","code":"SBP1","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the Staff Bike Park 1"},
                {"id":26,"name":"Visitor Car Park 1","code":"VCP1","type":"Area","parent_id":2,"org_corp_id":1,"description":"Area in site, the Visitor Car Park 1"},
                {"id":27,"name":"HR Office","code":"HROff","type":"Work Center","parent_id":4,"org_corp_id":1,"description":"Work center/segment in the area, the HR Office"},
                {"id":28,"name":"PCS Office","code":"PCSOff","type":"Work Center","parent_id":4,"org_corp_id":1,"description":"Work center/segment in the area, the PCS Office"},
                {"id":29,"name":"FA Office","code":"FAOff","type":"Work Center","parent_id":4,"org_corp_id":1,"description":"Work center/segment in the area, the FA Office"},
                {"id":30,"name":"SS Office","code":"SSOff","type":"Work Center","parent_id":4,"org_corp_id":1,"description":"Work center/segment in the area, the SS Office"},
                {"id":31,"name":"IT Office","code":"ITOff","type":"Work Center","parent_id":4,"org_corp_id":1,"description":"Work center/segment in the area, the IT Office"},
                {"id":32,"name":"PL Office","code":"PLOff","type":"Work Center","parent_id":4,"org_corp_id":1,"description":"Work center/segment in the area, the PL Office"},
                {"id":33,"name":"IE Office","code":"IEOff","type":"Work Center","parent_id":4,"org_corp_id":1,"description":"Work center/segment in the area, the IE Office"},
                {"id":34,"name":"QA Office","code":"QAOff","type":"Work Center","parent_id":3,"org_corp_id":1,"description":"Work center/segment in the area, the QA Office"},
                {"id":35,"name":"Lobby Reception Area","code":"Lobby","type":"Work Center","parent_id":3,"org_corp_id":1,"description":"Work center/segment in the area, the Lobby Reception Area"},
                {"id":36,"name":"Customer Room","code":"CR","type":"Work Center","parent_id":3,"org_corp_id":1,"description":"Work center/segment in the area, the Customer Room"}
            ]
        }';

        $data = json_decode($json, true);
        $locations = $data['locations'] ?? [];

        // 2. Build index maps for easy tree traversal
        $rootNodes = [];
        $childrenMap = [];
        $nodeDetails = [];

        foreach ($locations as $location) {
            $nodeDetails[$location['id']] = $location;
            if (is_null($location['parent_id'])) {
                $rootNodes[] = $location['id'];
            } else {
                $childrenMap[$location['parent_id']][] = $location['id'];
            }
        }

        // 3. Assemble the raw markdown structure string
        $markdown = "mindmap\n  root((Organization Structure))\n";

        foreach ($rootNodes as $rootNode) {
            $markdown .= $this->buildMermaidBranch($rootNode, $childrenMap, $nodeDetails, 2);
        }

        $this->mermaid = $markdown;
    }

    /**
     * Recursive method to properly format child nodes using exact spaces indentation.
     */
    private function buildMermaidBranch(int $id, array &$childrenMap, array &$nodeDetails, int $depth): string
    {
        $node = $nodeDetails[$id];
        $indent = str_repeat(' ', $depth * 2);

        // Escape quotes to prevent markdown breaks in string literals
        $cleanName = str_replace('"', '\"', $node['name']);

        // Use custom shape syntax variants depending on the organizational layer
        $shape = match ($node['type']) {
            'Enterprise' => "(($cleanName))", // Circle
            'Site' => "($cleanName)",   // Rounded Rect
            'Area' => "[$cleanName]",   // Square Box
            default => "[\"$cleanName\"]" // Standard Text
        };

        $output = $indent.$shape."\n";

        if (isset($childrenMap[$id])) {
            foreach ($childrenMap[$id] as $childId) {
                $output .= $this->buildMermaidBranch($childId, $childrenMap, $nodeDetails, $depth + 1);
            }
        }

        return $output;
    }
}
