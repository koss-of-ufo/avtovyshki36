// schema.js
const schemaData = [
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Автовышки36",
        "url": "https://avtovyshki36.ru/", 
        "logo": "https://avtovyshki36.ru/logo/logo-autovyshki-2.svg",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+7 906 679 3826",
            "contactType": "customer service",
            "areaServed": "RU",
            "availableLanguage": "Russian"
        },
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Отрадное, ул. Рубиновая, д. 11",
            "addressLocality": "Воронеж",
            "postalCode": "394094",
            "addressCountry": "RU"
        },
        "sameAs": [
            "https://t.me/+79066793826",
            "https://wa.me/79066793826"
        ]
    },
    {
        "@context": "https://schema.org",
        "@type": "Service",
        "serviceType": "Аренда автовышек",
        "provider": {
            "@type": "Organization",
            "name": "Автовышки36",
            "url": "https://avtovyshki36.ru/"
        },
        "areaServed": "Воронеж и область",
        "description": "Аренда автовышек высотой до 30 метров для высотных работ. Профессиональные операторы и гибкие условия аренды.",
        "offers": {
            "@type": "Offer",
            "priceCurrency": "RUB",
            "price": "от 1000",
            "url": "https://avtovyshki36.ru/"
        }
    },
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "Автовышка 30 метров",
        "description": "Аренда автовышки высотой до 30 метров для проведения высотных работ и монтажа.",
        "brand": "Автовышки36",
        "sku": "AV-30-01",
        "offers": {
            "@type": "Offer",
            "priceCurrency": "RUB",
            "price": "от 5000",
            "priceValidUntil": "2024-12-31",
            "url": "https://avtovyshki36.ru/"
        }
    }
];

// Добавляем данные в DOM как JSON-LD
schemaData.forEach(data => {
    const script = document.createElement('script');
    script.type = 'application/ld+json';
    script.innerHTML = JSON.stringify(data);
    document.head.appendChild(script);
});
