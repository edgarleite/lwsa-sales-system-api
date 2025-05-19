@component('mail::message')
# Relatório Diário de Vendas

Olá {{ $seller->name }},

Segue o resumo das suas vendas realizadas em {{ $date }}:

**Total de Vendas:** {{ $sales->count() }}  
**Valor Total:** R$ {{ number_format($totalSales, 2, ',', '.') }}  
**Comissão Total:** R$ {{ number_format($totalCommission, 2, ',', '.') }}

@component('mail::button', ['url' => url('/dashboard')])
Acessar Painel
@endcomponent

Atenciosamente,  
Equipe de Vendas
@endcomponent