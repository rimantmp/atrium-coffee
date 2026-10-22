<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class LoginPageTest extends TestCase
{
    public function test_guest_sees_the_staff_login_portal(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk()
            ->assertViewIs('auth.login')
            ->assertSee('Portal Staf')
            ->assertSee('Selamat datang kembali')
            ->assertSee('Kelola pesanan dengan cepat dan terarah.')
            ->assertSee('images/atrium-login-cafe.png')
            ->assertSee('data-password-toggle', false)
            ->assertSee('name="remember"', false);
    }
}
