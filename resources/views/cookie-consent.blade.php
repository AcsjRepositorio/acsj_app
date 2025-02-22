@if (Cookie::get('cookie_consent') === null)
    <div id="cookie-consent-banner" style="position: fixed; bottom: 0; width: 100%; background: #f5f5f5; padding: 1rem; text-align: center;">
        <span>
            Nós usamos cookies para melhorar sua experiência no nosso site. 
            Ao continuar navegando, você concorda com o uso de cookies.
        </span>
        <button 
            id="cookie-consent-button" 
            style="margin-left: 1rem; padding: 0.5rem 1rem; cursor: pointer;"
        >
            Ok, entendi!
        </button>
    </div>
@endif
