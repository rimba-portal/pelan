<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;

class Flowchart extends Page
{
    // The visual icon in the Filament sidebar
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-share';

    // The heading displayed at the top of your page
    protected static ?string $title = 'Location Structure';

    // The Blade view layout file
    protected string $view = 'bites::mermaid-md';

    public string $mermaid;

    public function mount(): void
    {
        // 1. Resolve path and load raw JSON from your seeds directory
        $jsonPath = database_path('seeds/locations.json');

        if (! File::exists($jsonPath)) {
            abort(500, "The location data file could not be found at: {$jsonPath}");
        }

        $json = File::get($jsonPath);
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

        // 3. Setup Flowchart base syntax
        $nodesPayload = '';

        // Clear root title
        $nodesPayload .= "  root[\"Organization Structure\"]\n";

        foreach ($rootNodes as $rootNode) {
            // Connect virtual root to the real top-level JSON elements
            $nodesPayload .= "  root --> node_{$rootNode}\n";
            $this->buildFlowchartBranch($rootNode, $childrenMap, $nodeDetails, $nodesPayload);
        }

        // Combine into final valid flowchart markdown format
        $this->mermaid = "flowchart TD\n".$nodesPayload;
    }

    /**
     * Recursive method to explicitly map flowchart node contents and connection lines.
     */
    private function buildFlowchartBranch(int $id, array &$childrenMap, array &$nodeDetails, string &$nodesPayload): void
    {
        $node = $nodeDetails[$id];

        // Clean text: strip out any raw quotes that might conflict with Mermaid markup rules
        $cleanName = str_replace(['"', "'"], '', $node['name']);

        // Standardized shapes wrapped securely with quotes for Mermaid 10.9.x safety
        $shape = match ($node['type']) {
            'Enterprise' => "((\"{$cleanName}\"))", // Double Circle
            'Site' => "(\"{$cleanName}\")",   // Rounded Rect
            'Area' => "[\"{$cleanName}\"]",   // Square Box
            default => "[\"{$cleanName}\"]"    // Fallback standard text box
        };

        // Assign text to the current specific node block identifier
        $nodesPayload .= "  node_{$id}{$shape}\n";

        // Recurse down and explicitly declare relationship linkages to children
        if (isset($childrenMap[$id])) {
            foreach ($childrenMap[$id] as $childId) {
                $nodesPayload .= "  node_{$id} --> node_{$childId}\n";
                $this->buildFlowchartBranch($childId, $childrenMap, $nodeDetails, $nodesPayload);
            }
        }
    }
}
