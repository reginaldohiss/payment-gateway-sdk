# Payment Gateway SDK

[![GitHub release](https://img.shields.io/github/v/release/reginaldohiss/payment-gateway-sdk)](https://github.com/reginaldohiss/payment-gateway-sdk/releases)
[![Packagist Version](https://img.shields.io/packagist/v/reginaldohiss/payment-gateway-sdk)](https://packagist.org/packages/reginaldohiss/payment-gateway-sdk)

🚀 **Payment Gateway SDK** é uma biblioteca PHP para integração com **múltiplos provedores de pagamento**, permitindo o uso de **Pix, Boleto e Cartão de Crédito**.

---

## 📌 Provedores Suportados
| Provedor       | Pix | Boleto | Cartão de Crédito | Ambiente |
|---------------|----|----|----|----|
| PagSeguro     | ✅ | ✅ | ✅ | Produção & Sandbox |
| Cielo         | ❌ | ✅ | ✅ | Produção & Sandbox |
| Itaú          | ✅ | ❌ | ❌ | Produção & Sandbox |
| Banco do Brasil | ✅ | ❌ | ❌ | Produção & Sandbox |
| Stone         | ✅ | ✅ | ✅ | Produção & Sandbox |
| Stripe        | ✅ | ❌ | ✅ | Produção & Sandbox |
---

## 📌 Instalação

Você pode instalar o SDK via **Composer**:

```bash
composer require seu-usuario/payment-gateway-sdk
```

## 📌 Como Usar

### **1️⃣ Criando um Gateway para um Provedor Específico**
Para iniciar um provedor de pagamento, você deve criar uma instância do `GatewayFactory` e especificar o **provedor** e o **ambiente**.

```php
require 'vendor/autoload.php';

use PaymentGateway\GatewayFactory;
use PaymentGateway\Config\Environment;

// Definir ambiente (Produção ou Sandbox)
$environment = new Environment(Environment::SANDBOX);

// Criar instância do provedor
$gateway = GatewayFactory::create('pagseguro', $environment);
```

### **2️⃣ Pagamento com Pix**
O pagamento via Pix gera um `QR Code` para que o cliente possa realizar o pagamento.

```php
$response = $gateway->payWithPix([
    "amount" => 150.00,
    "payer" => [
        "name" => "João Silva",
        "document" => "12345678909"
    ]
]);
```

### **3️⃣ Pagamento com Boleto**
O pagamento via `Boleto Bancário` gera um link para pagamento.

```php
$response = $gateway->payWithBoleto([
    "amount" => 250.00,
    "customer" => [
        "name" => "Maria Santos",
        "document" => "98765432100"
    ]
]);
```

### **4️⃣ Pagamento com Cartão de Crédito**
O pagamento via `cartão de crédito` processa a transação diretamente com o provedor.

```php
$response = $gateway->payWithCreditCard([
    "amount" => 500.00,
    "card" => [
        "number" => "4111111111111111",
        "holder" => "Carlos Souza",
        "expiry" => "12/28",
        "cvv" => "123",
        "brand" => "Visa"
    ]
]);
```

### **5️⃣ Consultar Status de uma Transação**
Para verificar o `status de uma transação`, utilize o ID da transação.

```php
$transactionId = "123ABC";
$response = $gateway->getTransactionDetails($transactionId);
```

## 📌 Contribuindo

Se você quiser contribuir com melhorias para o projeto, siga estes passos:

1. **Faça um fork** do repositório.

2. **Clone seu repositório localmente**:
   ```bash
   git clone https://github.com/reginaldohiss/payment-gateway-sdk.git
   cd payment-gateway-sdk
    ```
3. **Instale as dependências**:
   ```bash
   composer install
    ```
   
4. **Crie uma nova branch para sua feature**:
   ```bash
   git checkout -b minha-feature
    ```

