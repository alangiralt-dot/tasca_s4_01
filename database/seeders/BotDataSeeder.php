<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\FatherProduct; // Importem el nou Model

class BotDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Generar un array a partir del fitxer .json
        $jsonPath = database_path('data/fustes_en_brut.json');
        
        if (!File::exists($jsonPath)) {
            throw new \RuntimeException("Payload file not found at: {$jsonPath}");
        }

        $jsonContent = File::get($jsonPath);
        $products = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("JSON Parsing Error: " . json_last_error_msg());
        }

        // 2. Processar la informació en brut de cada producte pare i dels seus fills
        foreach ($products as $item) {
            
            // Pas A: Construcció de l'arbre de categories
            $parentId = null;

            foreach ($item['ruta'] as $categoryName) {
                $category = Category::firstOrCreate(
                    [
                        'category'  => $categoryName,
                        'father_id' => $parentId
                    ]
                );
                $parentId = $category->id;
            }

            // Pas B: escriure alguna cosa en els camps de FatherProduct (Taula 7)
            // Extraiem només el nom del fitxer .webp de la URL absoluta del JSON
            $imageName = basename($item['imatge']);

            $fatherProduct = FatherProduct::firstOrCreate(
                [
                    'name' => $item['producte_pare']
                ],
                [
                    'description' => $item['descripcio'] ?? null,
                    'image_path'  => 'products/' . $imageName,
                    'category_id' => $parentId // valor remanent després del loop
                ]
            );

            // Pas C: desem els valors de les caracteristiques del producte pare
            if (!empty($item['caracteristiques']) && is_array($item['caracteristiques'])) {
                
                foreach ($item['caracteristiques'] as $clauAtribut => $valorAtribut) {
                    
                    $attribute = \App\Models\Attribute::firstOrCreate([
                        'attribute' => $clauAtribut
                    ]);

                    // Com que no tenim Model per a 'attribute_father_product', fem servir la façana DB
                    \Illuminate\Support\Facades\DB::table('attribute_father_product')->updateOrInsert(
                        [
                            'attribute_id'      => $attribute->id,
                            'father_product_id' => $fatherProduct->id,
                        ],
                        [
                            'value'             => $valorAtribut ?? null
                        ]
                    );
                }
            }
            // Pas D: Processar els productes fill
            if (!empty($item['productes_fills']) && is_array($item['productes_fills'])) {
                
                foreach ($item['productes_fills'] as $fill) {
                    
                    // 1. disponibilitat
                    $availability = \App\Models\Availability::firstOrCreate([
                        'availability' => $fill['disponibilitat'] ?? 'Consultar'
                    ]);

                    // 2. mesures
                    $width  = 0;
                    $height = 0;
                    $length = 0;
                    $midesNetes = str_replace('MM', '', strtoupper($fill['mesures']));
                    
                    if (str_contains($midesNetes, 'Ø')) {
                        $diameNet = str_replace(['Ø', ' '], '', $midesNetes);
                        $width  = (int) trim($diameNet); // Si l'alçada és 0, l'amplada és el diàmetre
                        $height = 0;
                        $length = (int) ($item['caracteristiques']['Longitud (mm)'] ?? 0);
                    } else {
                        $dimensions = explode('X', $midesNetes);
                        if (count($dimensions) >= 2) {
                            $width  = (int) trim($dimensions[0]);
                            $height = (int) trim($dimensions[1]);
                        }                        
                        if (count($dimensions) === 3) {
                            $length = (int) trim($dimensions[2]);
                        } elseif (count($dimensions) === 2) {
                            $length = (int) ($item['caracteristiques']['Longitud (mm)'] ?? 0);
                        }
                    }
                    // 3. preu i unitats
                    $currentUnitPrice = 0.0;
                    $units = '€ / u'; 

                    $preuBrut = trim(str_replace("\xa0", ' ', $fill['preu'])); // "\xa0" és &nbsp;

                    if (preg_match('/([0-9]+,[0-9]+)(.+)/', $preuBrut, $matches)) {
                        $currentUnitPrice = (float) str_replace(',', '.', $matches[1]);
                        $units = trim($matches[2]); 
                    }
                    // 4. inventar el preu de cost
                    $costUnitPrice = $currentUnitPrice / 2.1;
                    // 5. omplir els camps de ChildProduct
                    \App\Models\ChildProduct::firstOrCreate(
                        [
                            'reference' => $fill['referencia']
                        ],
                        [
                            'width'              => $width,
                            'height'             => $height,
                            'length'             => $length,
                            'cost_unit_price'    => $costUnitPrice,
                            'current_unit_price' => $currentUnitPrice,
                            'units'              => $units,
                            'pack'               => (int) ($fill['pack'] ?? 1),
                            'stock'              => 0,
                            'father_product_id'  => $fatherProduct->id,
                            'availability_id'    => $availability->id,
                        ]
                    );
                }
            }
        } // foreach ($products as $item) {
    } // public function run(): void
} // class BotDataSeeder extends Seeder
