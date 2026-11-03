# -*- coding: utf-8 -*-
"""Gera uma planilha de amostra no formato de docs/modelo-planilha-produtos.csv.

Não é o catálogo do cliente. Serve para validar a importação de 300 produtos
variáveis até a planilha real chegar.
"""
import csv
from pathlib import Path

OUT = Path(__file__).with_name("catalogo-amostra.csv")
HEADERS = [
    "sku_pai", "sku", "tipo", "nome", "descricao_curta", "descricao",
    "categorias", "colecao", "tamanho", "cor", "preco", "preco_promocional",
    "estoque", "peso_kg", "comprimento_cm", "largura_cm", "altura_cm",
    "imagens", "composicao", "cuidados", "tabela_de_medidas",
    "titulo_seo", "descricao_seo", "destaque",
]

CATALOG = [
    ("Vestidos", "Verao 2026", 60, ["Vestido Midi Linho", "Vestido Floral", "Vestido Chemise", "Vestido Slip", "Vestido Envelope", "Vestido Longo Crepe"]),
    ("Blusas", "Verao 2026", 60, ["Blusa de Linho", "Blusa de Seda", "Camisa Oversized", "Regata Canelada", "Blusa Manga Bufante", "Top Estruturado"]),
    ("Calças", "Essenciais", 40, ["Calça Reta Linho", "Calça Wide Leg", "Calça Alfaiataria", "Calça Pantalona"]),
    ("Saias", "Verao 2026", 30, ["Saia Midi Plissada", "Saia Lápis", "Saia Envelope", "Saia Godê"]),
    ("Alfaiataria", "Alfaiataria", 40, ["Blazer Estruturado", "Colete Alfaiataria", "Calça de Alfaiataria", "Camisa Social Leve"]),
    ("Essenciais", "Essenciais", 40, ["Camiseta de Algodão", "Cardigan Leve", "Calça Reta", "Blusa Básica"]),
    ("Acessórios", "Essenciais", 30, ["Cinto de Couro", "Lenço de Seda", "Bolsa Estruturada", "Brinco Argola"]),
]
SIZES = ["P", "M", "G"]
COLORS = ["Preto", "Off-white", "Rosa", "Bege", "Marrom", "Azul-marinho", "Vinho", "Areia"]
COMPOSITION = [
    "100% linho",
    "100% viscose",
    "95% algodão, 5% elastano",
    "100% seda",
    "70% viscose, 30% linho",
]
CARE = [
    "Lavar à mão e secar à sombra.",
    "Lavar em ciclo delicado e não torcer.",
    "Lavagem a seco.",
    "Lavar do avesso com sabão neutro.",
]
FEATURED = {1, 16, 31, 46, 61, 76, 91, 106, 136, 166, 196, 241}


def money(value):
    return f"{value:.2f}"


def main():
    rows = []
    number = 1
    for category, collection, count, names in CATALOG:
        for offset in range(count):
            template = names[offset % len(names)]
            parent = f"MF-{number:04d}"
            name = f"{template} {number:03d}"
            short = f"{name}. Peça de amostra para validar tamanho, cor, estoque e preço."
            description = (
                f"<p>{short} Este item faz parte do catálogo de amostra e deve ser "
                "substituído pela planilha e pelas fotos do cliente.</p>"
            )
            composition = COMPOSITION[number % len(COMPOSITION)]
            care = CARE[number % len(CARE)]
            measures = "P 86 / M 90 / G 96"
            base = 129 + ((number * 17) % 360)
            price = base + (0.9 if number % 2 else 0.0)
            sale = round(price * 0.85, 2) if number % 7 == 0 else ""
            featured = "sim" if number in FEATURED else ""
            colors = [COLORS[number % len(COLORS)], COLORS[(number + 3) % len(COLORS)]]
            variations = [
                (SIZES[0], colors[0], 4 + (number % 6)),
                (SIZES[1], colors[0], 0 if number % 11 == 0 else 3 + (number % 5)),
                (SIZES[2], colors[1], 2 + (number % 4)),
            ]
            for size, color, stock in variations:
                sku = f"{parent}-{size}-{color[:3].upper()}"
                rows.append([
                    parent, sku, "variacao", name, short, description,
                    category, collection, size, color,
                    money(price), money(sale) if sale != "" else "",
                    stock, "0.35" if category == "Acessórios" else "0.45",
                    "28", "22", "4", "",
                    composition, care, measures,
                    name, short, featured,
                ])
            number += 1

    with OUT.open("w", encoding="utf-8", newline="") as handle:
        writer = csv.writer(handle)
        writer.writerow(HEADERS)
        writer.writerows(rows)

    parents = len({row[0] for row in rows})
    print(f"{OUT.name}: {parents} produtos pai, {len(rows)} variações")


if __name__ == "__main__":
    main()
