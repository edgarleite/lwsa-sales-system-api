@component('mail::message')
# Relatório Diário Administrativo

Segue o resumo das vendas realizadas em {{ $date }}:

**Total de Vendedores:** {{ $sellersCount }}  
**Total de Vendas:** {{ $sales->count() }}  
**Valor Total:** R$ {{ number_format($totalSales, 2, ',', '.') }}

@component('mail::button', ['url' => url('/admin/dashboard')])
Acessar Painel Administrativo
@endcomponent

Atenciosamente,  
Sistema de Vendas
@endcomponent