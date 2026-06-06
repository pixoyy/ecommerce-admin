<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\FileStorage;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brands = Brand::pluck('id', 'slug');
        $categories = Category::pluck('id', 'slug');
        $warehouses = Warehouse::pluck('id');
        $images = FileStorage::pluck('id');

        $products = [
            // 1. Erigo Essential Hoodie - Pakaian Pria
            [
                'brand_slug' => 'erigo',
                'cat_slug' => 'pakaian-pria',
                'name' => 'Erigo Essential Hoodie',
                'description' => 'Hoodie premium berbahan cotton fleece yang nyaman dipakai sehari-hari. Dengan potongan relaxed fit dan hoodie berlapis, cocok untuk gaya kasual maupun semi-formal.',
                'features' => "Bahan: Cotton Fleece 280gsm\nPotongan: Relaxed Fit\nHoodie berlapis\nKantong depan kangguru style\nRibbing pada ujung lengan dan bawah",
                'gender' => 2,
                'variants' => [
                    ['label' => 'S', 'price' => 150000, 'sku' => 'ERG-HD-S'],
                    ['label' => 'M', 'price' => 150000, 'sku' => 'ERG-HD-M'],
                    ['label' => 'L', 'price' => 155000, 'sku' => 'ERG-HD-L'],
                    ['label' => 'XL', 'price' => 155000, 'sku' => 'ERG-HD-XL'],
                ],
            ],
            // 2. 3Second Dress Casual Wanita - Pakaian Wanita
            [
                'brand_slug' => '3second',
                'cat_slug' => 'pakaian-wanita',
                'name' => '3Second Dress Casual Wanita',
                'description' => 'Dress casual dengan bahan rayon lembut dan flowy. Motif floral yang cantik cocok untuk hangout atau acara santai.',
                'features' => "Bahan: Rayon\nMotif: Floral\nPanjang: Midi\nTerdapat sabuk kain\nTersedia 3 varian warna",
                'gender' => 1,
                'variants' => [
                    ['label' => 'S', 'price' => 135000, 'sku' => '3SC-DRS-S'],
                    ['label' => 'M', 'price' => 135000, 'sku' => '3SC-DRS-M'],
                    ['label' => 'L', 'price' => 140000, 'sku' => '3SC-DRS-L'],
                ],
            ],
            // 3. Kemeja Batik Pria - Baju Muslim
            [
                'brand_slug' => 'batik-keris',
                'cat_slug' => 'baju-muslim',
                'name' => 'Kemeja Batik Pria Lengan Panjang',
                'description' => 'Kemeja batik premium dengan motif klasik yang elegan. Cocok untuk acara formal, kondangan, atau kegiatan sehari-hari. Bahan katun prima yang adem.',
                'features' => "Bahan: Katun Prima\nMotif: Batik Tulis\nLengan: Panjang\nKerah: Kentolan\nKancing: Full depan",
                'gender' => 3,
                'variants' => [
                    ['label' => 'M', 'price' => 250000, 'sku' => 'BTS-KM-M'],
                    ['label' => 'L', 'price' => 250000, 'sku' => 'BTS-KM-L'],
                    ['label' => 'XL', 'price' => 260000, 'sku' => 'BTS-KM-XL'],
                ],
            ],
            // 4. EIGER Ransel Alpine 25L - Tas & Dompet
            [
                'brand_slug' => 'eiger',
                'cat_slug' => 'tas-dompet',
                'name' => 'EIGER Ransel Alpine 25L',
                'description' => 'Ransel multifungsi dengan kapasitas 25 liter yang cocok untuk hiking, traveling, atau kegiatan outdoor sehari-hari. Dilengkapi rain cover dan banyak kompartemen.',
                'features' => "Kapasitas: 25 Liter\nMaterial: Ripstop Nylon\nRain Cover: Termasuk\nKompartemen Laptop: 15\"\nTali dada dan pinggang adjustable",
                'gender' => 3,
                'variants' => [
                    ['label' => 'Hitam', 'price' => 350000, 'sku' => 'EGR-RN-HTM'],
                    ['label' => 'Army', 'price' => 350000, 'sku' => 'EGR-RN-ARM'],
                    ['label' => 'Navy', 'price' => 360000, 'sku' => 'EGR-RN-NVY'],
                ],
            ],
            // 5. Nike Air Max 270 - Sepatu
            [
                'brand_slug' => 'nike',
                'cat_slug' => 'sepatu',
                'name' => 'Nike Air Max 270',
                'description' => 'Sepatu sneakers dengan teknologi Air Max unit terbesar di bagian heel. Desain modern dengan mesh breathable untuk kenyamanan maksimal sepanjang hari.',
                'features' => "Upper: Mesh & Synthetic\nSole: Rubber\nTechnology: Air Max 270\nClosure: Lace-up\nStyle: Casual/Sporty",
                'gender' => 2,
                'variants' => [
                    ['label' => '40', 'price' => 1800000, 'sku' => 'NKE-AM40'],
                    ['label' => '41', 'price' => 1800000, 'sku' => 'NKE-AM41'],
                    ['label' => '42', 'price' => 1850000, 'sku' => 'NKE-AM42'],
                    ['label' => '43', 'price' => 1850000, 'sku' => 'NKE-AM43'],
                ],
            ],
            // 6. Converse Chuck Taylor All Star - Sepatu
            [
                'brand_slug' => 'converse',
                'cat_slug' => 'sepatu',
                'name' => 'Converse Chuck Taylor All Star',
                'description' => 'Sepatu ikonik yang timeless. Desain klasik dengan kanvas berkualitas tinggi, sol karet yang tahan lama, dan舒适的 bantalan kain untuk kenyamanan sehari-hari.',
                'features' => "Upper: Canvas\nSole: Rubber Vulcanized\nStyle: High Top\nClosure: Lace-up\nBrand patch classic",
                'gender' => 1,
                'variants' => [
                    ['label' => '36', 'price' => 650000, 'sku' => 'CNV-CT36'],
                    ['label' => '37', 'price' => 650000, 'sku' => 'CNV-CT37'],
                    ['label' => '38', 'price' => 660000, 'sku' => 'CNV-CT38'],
                    ['label' => '39', 'price' => 660000, 'sku' => 'CNV-CT39'],
                ],
            ],
            // 7. Wardah Lightening Day Cream - Kesehatan & Kecantikan
            [
                'brand_slug' => 'wardah',
                'cat_slug' => 'kesehatan-kecantikan',
                'name' => 'Wardah Lightening Day Cream SPF30',
                'description' => 'Krim siang pemutih dengan SPF 30 yang melindungi kulit dari sinar UV sekaligus mencerahkan kulit secara bertahap. Dengan kandungan Vitamin C dan Niacinamide.',
                'features' => "SPF: 30 PA+++\nKandungan: Vitamin C, Niacinamide, Licorice Extract\nTekstur: Lightweight, tidak lengket\nHalal: Bersertifikat\nUntuk semua jenis kulit",
                'gender' => 1,
                'variants' => [
                    ['label' => '15ml', 'price' => 35000, 'sku' => 'WRD-DC15'],
                    ['label' => '30ml', 'price' => 55000, 'sku' => 'WRD-DC30'],
                ],
            ],
            // 8. Somethinc Niacinamide Serum - Kesehatan & Kecantikan
            [
                'brand_slug' => 'somethinc',
                'cat_slug' => 'kesehatan-kecantikan',
                'name' => 'Somethinc Niacinamide Serum 10%',
                'description' => 'Serum dengan konsentrasi Niacinamide 10% yang tinggi untuk membantu mengatasi jerawat, menyamarkan pori-pori, dan mencerahkan kulit. Oil-free dan non-comedogenic.',
                'features' => "Niacinamide: 10%\nKandungan: Niacinamide, Zinc PCA, Centella Asiatica\nOil-free: Ya\nNon-comedogenic: Ya\nVegan & Cruelty-free",
                'gender' => 1,
                'variants' => [
                    ['label' => '15ml', 'price' => 75000, 'sku' => 'SMC-NC15'],
                    ['label' => '30ml', 'price' => 120000, 'sku' => 'SMC-NC30'],
                ],
            ],
            // 9. EIGER Sling Bag Urban - Tas & Dompet
            [
                'brand_slug' => 'eiger',
                'cat_slug' => 'tas-dompet',
                'name' => 'EIGER Sling Bag Urban',
                'description' => 'Tas selempang compact yang stylish untuk kegiatan sehari-hari. Dengan banyak kompartemen untuk menyimpan HP, dompet, dan barang penting lainnya.',
                'features' => "Material: Polyester 600D\nKapasitas: 5 Liter\nKompartemen: 3 utama + 2 depan\nAdjustable strap\nResleting anti-air",
                'gender' => 3,
                'variants' => [
                    ['label' => 'Hitam', 'price' => 95000, 'sku' => 'EGR-SL-HTM'],
                    ['label' => 'Abu-Abu', 'price' => 95000, 'sku' => 'EGR-SL-ABU'],
                    ['label' => 'Navy', 'price' => 95000, 'sku' => 'EGR-SL-NVY'],
                ],
            ],
            // 10. Erigo Muslim Set Koko - Baju Muslim
            [
                'brand_slug' => 'erigo',
                'cat_slug' => 'baju-muslim',
                'name' => 'Erigo Muslim Set Koko',
                'description' => 'Setelan muslim modern terdiri dari kemeja koko dan celana panjang. Bahan katun stretch yang nyaman dipakai untuk sholat, ngaji, atau acara keagamaan.',
                'features' => "Bahan: Katun Stretch\nSet: Kemeja Koko + Celana\nPotongan: Slim Fit\nKerah: Mandarin\nTersedia 3 warna",
                'gender' => 2,
                'variants' => [
                    ['label' => 'M', 'price' => 185000, 'sku' => 'ERG-MSL-M'],
                    ['label' => 'L', 'price' => 185000, 'sku' => 'ERG-MSL-L'],
                    ['label' => 'XL', 'price' => 190000, 'sku' => 'ERG-MSL-XL'],
                ],
            ],
            // 11. 3Second Jogger Pants - Pakaian Pria
            [
                'brand_slug' => '3second',
                'cat_slug' => 'pakaian-pria',
                'name' => '3Second Jogger Pants',
                'description' => 'Celana jogger casual dengan bahan fleece yang tebal dan hangat. Dilengkapi elastic waistband dengan tali serut dan kantong samping yang fungsional.',
                'features' => "Bahan: French Terry Fleece\nWaist: Elastic + Drawstring\nKantong: 2 samping + 1 belakang\nCuff: Ribbed ankle\nPotongan: Regular fit",
                'gender' => 2,
                'variants' => [
                    ['label' => 'M', 'price' => 125000, 'sku' => '3SC-JG-M'],
                    ['label' => 'L', 'price' => 125000, 'sku' => '3SC-JG-L'],
                    ['label' => 'XL', 'price' => 130000, 'sku' => '3SC-JG-XL'],
                    ['label' => 'XXL', 'price' => 130000, 'sku' => '3SC-JG-XXL'],
                ],
            ],
            // 12. Erigo Hoodie Dress - Pakaian Wanita
            [
                'brand_slug' => 'erigo',
                'cat_slug' => 'pakaian-wanita',
                'name' => 'Erigo Hoodie Dress',
                'description' => 'Hoodie dress yang cozy dan stylish. Dengan potongan oversized yang trendi, cocok dipakai untuk daily look maupun hangout santai.',
                'features' => "Bahan: Cotton Fleece 320gsm\nPotongan: Oversized\nPanjang: Midi\nHoodie dengan tali serut\nKantong depan kangguru",
                'gender' => 1,
                'variants' => [
                    ['label' => 'S', 'price' => 175000, 'sku' => 'ERG-HDS-S'],
                    ['label' => 'M', 'price' => 175000, 'sku' => 'ERG-HDS-M'],
                    ['label' => 'L', 'price' => 180000, 'sku' => 'ERG-HDS-L'],
                ],
            ],
            // 13. Adidas Ultraboost 22 - Sepatu
            [
                'brand_slug' => 'adidas',
                'cat_slug' => 'sepatu',
                'name' => 'Adidas Ultraboost 22',
                'description' => 'Sepatu lari premium dengan teknologi Boost terbaru yang memberikan energy return maksimal. Upper Primeknit yang lembut dan pas di kaki seperti kaus kaki.',
                'features' => "Upper: Primeknit+\nMidsole: Boost\nOutsole: Continental Rubber\nHeel Drop: 10mm\nWeight: ~290g (size 38)",
                'gender' => 1,
                'variants' => [
                    ['label' => '37', 'price' => 2200000, 'sku' => 'ADS-UB37'],
                    ['label' => '38', 'price' => 2200000, 'sku' => 'ADS-UB38'],
                    ['label' => '39', 'price' => 2250000, 'sku' => 'ADS-UB39'],
                ],
            ],
            // 14. EIGER Tumbler Stainless 500ml - Peralatan Olahraga
            [
                'brand_slug' => 'eiger',
                'cat_slug' => 'peralatan-olahraga',
                'name' => 'EIGER Tumbler Stainless 500ml',
                'description' => 'Tumbler stainless steel double-wall yang menjaga minuman tetap panas hingga 12 jam atau dingin hingga 24 jam. Cocok untuk kegiatan outdoor, gym, atau kantor.',
                'features' => "Material: Stainless Steel 304\nKapasitas: 500ml\nDouble-wall vacuum insulation\nBPA-free\nTahan panas 12 jam / dingin 24 jam",
                'gender' => 3,
                'variants' => [
                    ['label' => 'Biru', 'price' => 65000, 'sku' => 'EGR-TB-BRU'],
                    ['label' => 'Hijau', 'price' => 65000, 'sku' => 'EGR-TB-HJU'],
                    ['label' => 'Hitam', 'price' => 65000, 'sku' => 'EGR-TB-HTM'],
                ],
            ],
            // 15. Vans Bucket Hat - Aksesoris
            [
                'brand_slug' => 'vans',
                'cat_slug' => 'aksesoris',
                'name' => 'Vans Bucket Hat',
                'description' => 'Topi bucket ikonik khas Vans dengan motif checkerboard. Terbuat dari bahan katun twill yang nyaman dan ringan. Pelindung wajah dari sinar matahari.',
                'features' => "Bahan: Katun Twill\nMotif: Checkerboard\nLingkar: 56-58cm (adjustable)\nRingan & mudah dilipat\nOne size fits most",
                'gender' => 3,
                'variants' => [
                    ['label' => 'Hitam', 'price' => 120000, 'sku' => 'VNS-BK-HTM'],
                    ['label' => 'Navy', 'price' => 120000, 'sku' => 'VNS-BK-NVY'],
                    ['label' => 'Army', 'price' => 120000, 'sku' => 'VNS-BK-ARM'],
                ],
            ],
        ];

        $imgIndex = 8;
        foreach ($products as $pData) {
            $thumbnail = $images->get($imgIndex);

            $product = Product::create([
                'brand_id' => $brands->get($pData['brand_slug']),
                'category_id' => $categories->get($pData['cat_slug']),
                'thumbnail' => $thumbnail,
                'name' => $pData['name'],
                'slug' => Str::slug($pData['name']),
                'description' => $pData['description'],
                'features' => $pData['features'],
                'gender' => $pData['gender'],
                'is_active' => 1,
            ]);

            $imgIndex++;

            foreach ($pData['variants'] as $vData) {
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'label' => $vData['label'],
                    'price' => $vData['price'],
                    'sku' => $vData['sku'],
                    'is_active' => 1,
                ]);

                foreach ($warehouses as $warehouseId) {
                    WarehouseStock::create([
                        'warehouse_id' => $warehouseId,
                        'product_variant_id' => $variant->id,
                        'quantity' => fake()->numberBetween(5, 100),
                    ]);
                }
            }

            for ($pi = 0; $pi < 3; $pi++) {
                $pImg = $images->get($imgIndex + $pi);
                if ($pImg) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $pImg,
                        'sort_order' => $pi + 1,
                    ]);
                }
            }

            $imgIndex += 3;
        }
    }
}
