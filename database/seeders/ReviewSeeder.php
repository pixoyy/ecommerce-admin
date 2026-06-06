<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $orderItems = OrderItem::with(['order', 'productVariant.product'])->get();

        $reviews = [
            ['rating' => 5, 'reason' => 'Produk bagus, bahan nyaman, sesuai dengan foto. Pengiriman cepat banget!'],
            ['rating' => 4, 'reason' => 'Bagus cuma ukurannya sedikit kebesaran. Tapi overall oke lah.'],
            ['rating' => 5, 'reason' => 'Worth it banget! Kualitas premium dengan harga terjangkau. Recommended!'],
            ['rating' => 3, 'reason' => 'Biasa aja, tidak sesuai ekspektasi. Mungkin saya kurang beruntung.'],
            ['rating' => 4, 'reason' => 'Sudah beli kedua kalinya, kualitas konsisten. Love it!'],
            ['rating' => 5, 'reason' => 'Fast respon, fast delivery, produk original. Mantul!'],
            ['rating' => 2, 'reason' => 'Sayang sekali ukuran tidak sesuai dengan size chart. Harusnya diinfokan lebih jelas.'],
            ['rating' => 4, 'reason' => 'Barang sampai dengan selamat, packaging aman. Thank you seller.'],
            ['rating' => 5, 'reason' => 'Suka banget sama produknya! Warna sesuai foto, bahan adem.'],
            ['rating' => 4, 'reason' => 'Cocok untuk daily wear. Recommended buat yang cari outfit simple.'],
            ['rating' => 5, 'reason' => 'Kualitas top markotop! Seller jujur dan amanah.'],
            ['rating' => 3, 'reason' => 'Lumayan untuk harganya. Pengiriman agak lambat.'],
            ['rating' => 5, 'reason' => 'Bahan tebal dan nyaman. Jahitan rapi. Size M fit untuk BB 65kg.'],
            ['rating' => 4, 'reason' => 'Warna persis seperti di foto. Bahannya adem dan tidak nerawang.'],
            ['rating' => 5, 'reason' => 'Produk original dan berkualitas. Seller recommended banget!'],
            ['rating' => 2, 'reason' => 'Sayang ada sedikit cacat di bagian jahitan. Tapi seller respon cepat.'],
            ['rating' => 5, 'reason' => 'Best purchase ever! Udah repeat order 3 kali.'],
            ['rating' => 4, 'reason' => 'Overall bagus. Semoga next ada varian warna lain.'],
            ['rating' => 5, 'reason' => 'Sempurna! Sesuai deskripsi. Respon penjual cepat dan ramah.'],
            ['rating' => 3, 'reason' => 'Standar, tidak ada yang spesial. OK untuk harga segitu.'],
        ];

        foreach ($reviews as $i => $reviewData) {
            $orderItem = $orderItems->get($i % $orderItems->count());
            if (!$orderItem) continue;

            Review::create([
                'user_id' => $orderItem->order->user_id,
                'product_id' => $orderItem->productVariant->product_id,
                'order_item_id' => $orderItem->id,
                'rating' => $reviewData['rating'],
                'reason' => $reviewData['reason'],
                'is_visible' => fake()->boolean(90) ? 1 : 0,
            ]);
        }
    }
}
