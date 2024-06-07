<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

    <h1>Setup Laravel10-Satu-Sehat</h1>
    <ol>
        <li><strong>Clone Repository:</strong>
            <pre><code>git clone https://github.com/alfiansyah-arch/Laravel10-Satu-Sehat</code></pre>
        </li>
        <li><strong>Copy and Rename .env File:</strong>
            <pre><code>cp .env.example .env</code></pre>
        </li>
        <li><strong>Configure .env File:</strong>
            <ul>
                <li>Set <code>SATUSEHAT_ORGANIZATION_ID</code> with your organization ID from Satu Sehat:
                    <pre><code>SATUSEHAT_ORGANIZATION_ID=xxxx</code></pre>
                </li>
                <li>Set <code>SATUSEHAT_CLIENT_KEY</code> with your client ID from Satu Sehat:
                    <pre><code>SATUSEHAT_CLIENT_KEY=xxxx</code></pre>
                </li>
                <li>Set <code>SATUSEHAT_CLIENT_SECRET</code> with your client secret from Satu Sehat:
                    <pre><code>SATUSEHAT_CLIENT_SECRET=xxxx</code></pre>
                </li>
            </ul>
        </li>
        <li><strong>Configure Database:</strong>
            <p>Ensure your database settings in the <code>.env</code> file are correct.</p>
        </li>
        <li><strong>Run Migrations:</strong>
            <pre><code>php artisan migrate</code></pre>
        </li>
        <li><strong>Start Local Server:</strong>
            <pre><code>php artisan serve</code></pre>
        </li>
    </ol>
    <p>Selamat mencoba!</p>
</body>
</html>
