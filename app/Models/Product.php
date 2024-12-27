<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Google\Client;
use Google\Service\Sheets;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'image',
        'stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saveToSheet()
    {
        $client = new Client();
        $client->setApplicationName('Your Application Name');
        $client->setScopes(Sheets::SPREADSHEETS);
        $client->setAuthConfig(config('google.key_file'));

        $service = new Sheets($client);
        $spreadsheetId = config('google.spreadsheet_id');

        // Convert the current product instance data into a format for Google Sheets
        $values = [
            [$this->category_id, $this->name, $this->price, $this->image, $this->stock]
        ];

        $body = new Sheets\ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'RAW'];
        
        // Specify your range, e.g., "Sheet1!A1"
        $range = 'Sheet1!A1';  // Adjust range depending on where you want to start appending

        // Append the data to the sheet
        try {
            $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
            return $result->getUpdates()->getUpdatedCells();
        } catch (Exception $e) {
            // Handle error
            return 'Error: ' . $e->getMessage();
        }
    }
}