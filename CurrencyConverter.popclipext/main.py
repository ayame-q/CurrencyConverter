#!/usr/bin/python3

import json
import os
import sys
from decimal import Decimal, ROUND_HALF_UP
from urllib.error import URLError, HTTPError
from urllib.request import urlopen


def env(name: str) -> str:
    return os.getenv(name, "").strip()


text = env("POPCLIP_TEXT")
to = env("POPCLIP_OPTION_CURRENCY")
fee = env("POPCLIP_OPTION_FEE")

if not text:
    sys.exit(0)

frommark = text[:1]
price = text[1:].replace(",", "")

if frommark == "$":
    from_currency = env("POPCLIP_OPTION_DOLLAR").lower()
elif frommark == "€":
    from_currency = "eur"
elif frommark == "¥":
    from_currency = env("POPCLIP_OPTION_YEN").lower()
elif frommark == "£":
    from_currency = "gbp"
elif frommark == "₽":
    from_currency = "rub"
elif frommark == "₹":
    from_currency = "inr"
elif frommark == "₩":
    from_currency = "krw"
elif frommark == "R":
    from_currency = "zar"
elif frommark == "฿":
    from_currency = "thb"
elif frommark == "₱":
    from_currency = "php"
elif frommark == "₺":
    from_currency = "try"
elif frommark == "₺":
    from_currency = "ngn"
else:
    sys.exit(0)

url = f"http://api.aoikujira.com/kawase/json/{from_currency}"
try:
    with urlopen(url) as response:
        if response.status >= 400:
            sys.exit(0)
        data = json.load(response)
except (HTTPError, URLError, ValueError):
    sys.exit(0)

try:
    base = data[to]
except KeyError:
    sys.exit(0)

if to == "USD":
    tomark = "$"
    digit = 2
elif to == "EUR":
    tomark = "€"
    digit = 2
elif to == "JPY":
    tomark = "¥"
    digit = 0
elif to == "GBP":
    tomark = "€"
    digit = 2
elif to == "CAD":
    tomark = "$"
    digit = 2
elif to == "AUD":
    tomark = "$"
    digit = 2
elif to == "CNY":
    tomark = "¥"
    digit = 2
elif to == "RUB":
    tomark = "₽"
    digit = 2
elif to == "BRL":
    tomark = "$"
    digit = 2
elif to == "INR":
    tomark = "₹"
    digit = 2
elif to == "KRW":
    tomark = "₩"
    digit = 2
elif to == "TWD":
    tomark = "$"
    digit = 2
elif to == "NZD":
    tomark = "$"
    digit = 2
elif to == "HKD":
    tomark = "$"
    digit = 2
elif to == "THB":
    tomark = "฿"
    digit = 2
elif to == "PHP":
    tomark = "₱"
    digit = 2
elif to == "ZAR":
    tomark = "R"
    digit = 2
elif to == "TRY":
    tomark = "₺"
    digit = 2
elif to == "NGN":
    tomark = "₺"
    digit = 2
elif to == "SGD":
    tomark = "$"
    digit = 2
elif to == "MXN":
    tomark = "$"
    digit = 2
else:
    sys.exit(0)

price_decimal = Decimal(price)
base_decimal = Decimal(str(base))
fee_decimal = Decimal(fee)
total = price_decimal * base_decimal + price_decimal * base_decimal * fee_decimal * Decimal("0.01")

if digit == 0:
    quantized = total.quantize(Decimal("1"), rounding=ROUND_HALF_UP)
    formatted = format(quantized, ",.0f")
else:
    quantized = total.quantize(Decimal("0.01"), rounding=ROUND_HALF_UP)
    formatted = format(quantized, ",.2f")

sys.stdout.write(f"{tomark}{formatted}")
