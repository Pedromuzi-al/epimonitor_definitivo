# EpiMonitor - Instruções de Setup

## 🎯 Bem-vindo ao EpiMonitor!

Seu sistema de monitoramento epidemiológico inteligente foi estruturado com sucesso! Agora siga estes passos para colocar em funcionamento.

## 📋 Pré-requisitos

- **XAMPP/WAMP** com MySQL rodando
- **PHP** 8.0 ou superior
- **Composer** instalado

## 🚀 Passos de Configuração

### 1. **Inicie o MySQL (WAMP)**
   - Abra WAMP Manager
   - Clique em MySQL para iniciar o banco de dados
   - Verifique se o ícone está verde

### 2. **Crie o banco de dados**
   ```bash
   # Acesse o phpMyAdmin em http://localhost/phpmyadmin
   # Ou via terminal MySQL:
   mysql -u root
   CREATE DATABASE epimonitor;
   EXIT;
   ```

### 3. **Configure o arquivo .env**
   
   Abra `c:\wamp\www\EpiMonitor definitivo\.env` e ajuste:

   ```env
   DB_DATABASE=epimonitor
   DB_USERNAME=root
   DB_PASSWORD=
   APP_URL=http://127.0.0.1:8000
   ```

### 4. **Execute as Migrations**
   
   Abra o terminal na pasta do projeto e execute:

   ```bash
   php artisan migrate --seed
   ```

   Isso irá:
   - Criar todas as tabelas
   - Popular a tabela de doenças com seus pesos de sintomas
   - Configurar o banco de dados completamente

### 5. **Inicie o servidor Laravel**
   
   ```bash
   php artisan serve
   ```

   O servidor estará disponível em: **http://127.0.0.1:8000**

## 🌐 Acessando a Aplicação

Abra seu navegador (Brave, Chrome, etc) e acesse:
```
http://127.0.0.1:8000
```

Você verá a página inicial do EpiMonitor com todas as funcionalidades!

## 📊 Funcionalidades Principais

### 1. **Cadastro de Pessoas**
- Nome, CPF (validado), Idade, Telefone, Bairro
- Validação de CPF duplicado
- Gerenciamento completo

### 2. **Diagnóstico Inteligente**
- Seleção de 12 sintomas diferentes
- Análise inteligente com sistema de pontuação
- Resultado com probabilidade em porcentagem
- Histórico completo por pessoa

### 3. **Análise de Doenças**
Monitora 8 doenças:
- Gripe
- Resfriado
- Dengue
- Gastroenterite
- COVID-19
- Leptospirose
- Infecção Intestinal
- Intoxicação Alimentar

### 4. **Sistema de Alertas por Bairro**
- ⚠️ **Moderado**: 10+ sintomas
- 🚨 **Alto**: 20+ sintomas
- 🔥 **Crítico**: 30+ sintomas

### 5. **Estatísticas e Relatórios**
- Ranking de doenças mais registradas
- Incidência por bairro
- Gráficos interativos com Chart.js
- Análises em tempo real

## 🛠️ Estrutura do Projeto

```
EpiMonitor definitivo/
├── app/
│   ├── Http/Controllers/     # Controllers da aplicação
│   ├── Models/               # Models (Person, Disease, Diagnosis, etc)
│   ├── Rules/                # Regras de validação (CPF)
│   └── Services/             # Serviços (DiagnosisService)
├── resources/views/          # Templates Blade
│   ├── layouts/              # Layout principal
│   ├── people/               # Views de pessoas
│   ├── diagnoses/            # Views de diagnósticos
│   └── statistics/           # Views de estatísticas
├── database/
│   ├── migrations/           # Estrutura de tabelas
│   └── seeders/              # PopularBD (Doenças)
├── routes/
│   └── web.php               # Rotas da aplicação
└── config/
    └── database.php          # Configuração de BD
```

## 🔒 Validações Implementadas

✅ **CPF**: Validação matemática correta do CPF  
✅ **CPF Duplicado**: Não permite CPF já cadastrado  
✅ **Idade**: Valor entre 0 e 150 anos  
✅ **Sintomas**: Mínimo 1 sintoma obrigatório  
✅ **Entrada de Dados**: Validação de campos obrigatórios

## 🎨 Interface

- **Bootstrap 5**: Design responsivo e profissional
- **Font Awesome**: Ícones de alta qualidade
- **Chart.js**: Gráficos interativos
- **Mobile-Friendly**: Funciona em qualquer dispositivo

## 📱 Navegação Principal

- **Home**: Dashboard com informações gerais
- **Pessoas**: Gerenciar cadastro de pessoas
- **Diagnósticos**: Realizar e visualizar diagnósticos
- **Estatísticas**: Análises e relatórios do sistema

## ⚠️ Aviso Importante

> **ESTE SISTEMA É UMA SIMULAÇÃO EDUCACIONAL**
> 
> Os diagnósticos fornecidos são baseados em análise simples de sintomas e não substituem diagnóstico médico profissional. Sempre consulte um médico qualificado para diagnóstico e tratamento adequados.

## 🔄 Próximas Melhorias Sugeridas

- [ ] Autenticação de usuários (admin/usuário)
- [ ] Exportar relatórios em PDF
- [ ] Integração com mapas (geolocalização)
- [ ] Sistema de notificações por email
- [ ] API REST completa
- [ ] Suporte a múltiplos idiomas
- [ ] Backup automático de dados

## 📞 Suporte

Se encontrar algum problema:

1. Verifique se MySQL está rodando
2. Verifique o arquivo `.env`
3. Verifique se as migrations foram executadas: `php artisan migrate:status`
4. Limpe o cache: `php artisan cache:clear`

## 🎓 Referência Original

Este projeto foi adaptado do seu projeto C original em:
```
https://github.com/Pedromuzi-al/epimonitor-em-c
```

Mantendo todas as funcionalidades, validações e lógica de diagnóstico! 

---

**Desenvolvido com ❤️ para vigilância epidemiológica**
