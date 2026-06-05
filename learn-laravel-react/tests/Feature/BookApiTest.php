<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase; // Tự động xóa sạch DB sau mỗi lần test để dữ liệu không bị rác

    public function test_can_get_book_list_api()
    {
        // Gọi thử vào API lấy danh sách sách
        $response = $this->getJson('/api/books');

        // Kiểm tra xem trạng thái trả về có phải là 200 (Thành công) hay không
        $response->assertStatus(200);
    }
}