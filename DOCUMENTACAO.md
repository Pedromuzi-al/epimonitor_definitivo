# 🩺 EpiMonitor - Sistema de Monitoramento Epidemiológico

## ✨ Projeto Completo e Funcional

Seu projeto **EpiMonitor** foi estruturado com sucesso como uma aplicação web Laravel moderna! Todas as ideias e funcionalidades do seu projeto original em C foram implementadas com uma interface profissional e responsiva.

---

## 📦 O Que Foi Criado

### 1. **Base de Dados e Modelos**
```
✅ Tabela: people (Pessoas)
   - name (Nome)
   - cpf (CPF - Validado e Único)
   - age (Idade)
   - phone (Telefone)
   - neighborhood (Bairro)

✅ Tabela: diseases (Doenças)
   - name (Nome da Doença)
   - description (Descrição)
   - symptom_weights (Pesos dos Sintomas em JSON)

✅ Tabela: diagnoses (Diagnósticos)
   - person_id (FK para pessoas)
   - disease_id (FK para doenças)
   - probability (Probabilidade em %)
   - symptoms (Array de sintomas)
   - neighborhood (Bairro)
   - alert_level (Nível de alerta)

✅ Tabela: symptom_records (Registro de Sintomas)
   - diagnosis_id (FK para diagnósticos)
   - symptoms (Array de sintomas analisados)
```

### 2. **Controladores (Controllers)**

#### **PersonController** - Gerenciamento de Pessoas
- `index()` - Listar todas as pessoas
- `create()` - Formulário de cadastro
- `store()` - Salvar nova pessoa
- `show()` - Ver perfil completo
- `edit()` - Editar pessoa
- `update()` - Salvar alterações
- `destroy()` - Deletar pessoa

#### **DiagnosisController** - Diagnósticos
- `index()` - Listar diagnósticos
- `create()` - Formulário com seleção de sintomas
- `store()` - Processar diagnóstico e salvar
- `show()` - Ver resultado detalhado
- `destroy()` - Deletar diagnóstico

#### **StatisticsController** - Estatísticas
- `dashboard()` - Painel com gráficos e relatórios

### 3. **Serviços (Services)**

#### **DiagnosisService**
Lógica inteligente de diagnóstico:
- `calculateDiagnosis()` - Calcula todas as doenças com probab.
- `calculateScore()` - Pontuação para cada sintoma
- `calculateProbability()` - Converte pontuação em %
- `getAlertLevel()` - Determina nível de risco do bairro
- `getDiseaseStatistics()` - Ranking de doenças
- `getNeighborhoodStatistics()` - Incidência por bairro

### 4. **Validações**

#### **Regra ValidCPF**
- Valida a estrutura do CPF brasileiro
- Calcula e verifica dígitos verificadores
- Bloqueia CPFs sequenciais

#### **Validações no Controller**
```php
'cpf' => 'required|cpf|unique:people'     // CPF validado e único
'age' => 'required|integer|min:0|max:150'  // Idade válida
'symptoms' => 'required|array|min:1'       // Mínimo 1 sintoma
```

### 5. **12 Sintomas Monitorados**
1. Febre
2. Dor de cabeça
3. Dor de barriga
4. Tosse
5. Vômito
6. Diarreia
7. Dor no corpo
8. Dor de garganta
9. Coriza
10. Fadiga
11. Calafrios
12. Perda de olfato

### 6. **8 Doenças com Análise Inteligente**
1. **Gripe** - Sintomas respiratórios + febre alta
2. **Resfriado** - Sintomas leves respiratórios
3. **Dengue** - Febre alta + dor no corpo + fadiga
4. **Gastroenterite** - Sintomas gastrointestinais
5. **COVID-19** - Respiratory + perda de olfato
6. **Leptospirose** - Febre + dor corpo + GI
7. **Infecção Intestinal** - Sintomas GI puros
8. **Intoxicação Alimentar** - Vômito + diarreia

### 7. **Sistema de Alertas por Bairro**
```
🟢 Baixo      (< 10 sintomas)
🟡 Moderado   (10-19 sintomas)
🟠 Alto       (20-29 sintomas)
🔴 Crítico    (30+ sintomas)
```

### 8. **Views (Interface)**

#### **Layout Principal** (`layouts/app.blade.php`)
- Navbar com navegação
- Alertas automáticos (sucesso/erro)
- Footer informativo
- Design responsivo (Bootstrap 5)
- Ícones (Font Awesome)

#### **Pessoas**
- `people/index.blade.php` - Lista de pessoas com CRUD
- `people/create.blade.php` - Formulário de cadastro
- `people/edit.blade.php` - Editar informações
- `people/show.blade.php` - Perfil com histórico de diagnósticos

#### **Diagnósticos**
- `diagnoses/index.blade.php` - Histórico de diagnósticos
- `diagnoses/create.blade.php` - Seletor inteligente de sintomas
- `diagnoses/show.blade.php` - Resultado com análise completa

#### **Estatísticas**
- `statistics/dashboard.blade.php` - Gráficos (Chart.js) + Tabelas

#### **Home**
- `welcome.blade.php` - Página inicial com features

### 9. **Rotas Criadas**
```php
GET  /                    → Home
GET  /people              → Listar pessoas
POST /people              → Criar pessoa
GET  /people/create       → Formulário
GET  /people/{id}         → Ver pessoa
GET  /people/{id}/edit    → Editar
PUT  /people/{id}         → Atualizar
DELETE /people/{id}       → Deletar

GET  /diagnoses           → Histórico
POST /diagnoses           → Realizar diagnóstico
GET  /diagnoses/create    → Formulário diagnóstico
GET  /diagnoses/{id}      → Ver resultado

GET  /statistics          → Dashboard
```

---

## 🎨 Design e UX

### Cores e Estilo
- **Navbar**: Gradiente azul profissional
- **Cards**: Sombra e hover effect
- **Botões**: Cores temáticas (primário, sucesso, perigo)
- **Badges**: Alertas com cores apropriadas
- **Tabelas**: Responsivas com efeito hover

### Responsividade
- ✅ Mobile-first design
- ✅ Funciona em qualquer tamanho de tela
- ✅ Navegação adaptativa
- ✅ Formulários otimizados

### Acessibilidade
- ✅ Ícones com texto alternativo
- ✅ Cores com bom contraste
- ✅ Formulários bem estruturados
- ✅ Validações visuais claras

---

## 🚀 Como Usar

### Passo 1: Configurar Banco de Dados
```bash
# 1. Crie o banco 'epimonitor' no MySQL
# 2. Atualize o .env:
DB_DATABASE=epimonitor
DB_USERNAME=root
DB_PASSWORD=
```

### Passo 2: Executar Migrations
```bash
cd "c:\wamp\www\EpiMonitor definitivo"
php artisan migrate --seed
```

### Passo 3: Iniciar Servidor
```bash
php artisan serve
```

### Passo 4: Acessar no Navegador
```
http://127.0.0.1:8000
```

---

## 📊 Exemplo de Uso

### Cenário: Registrar um Diagnóstico

1. **Cadastre uma pessoa**
   - Clique em "Pessoas" → "Adicionar Pessoa"
   - Preenchaperfil (nome, CPF, idade, etc)

2. **Realize um diagnóstico**
   - Clique em "Diagnósticos" → "Novo Diagnóstico"
   - Selecione a pessoa
   - Marque sintomas (ex: Febre, Tosse, Dor de cabeça)
   - Clique em "Realizar Diagnóstico"

3. **Veja o resultado**
   - Sistema calcula probabilidade para todas as 8 doenças
   - Mostra a doença mais provável com %
   - Define alerta automático do bairro

4. **Visualize estatísticas**
   - Clique em "Estatísticas"
   - Veja ranking de doenças
   - Veja incidência por bairro

---

## 🔐 Segurança

### Validações Implementadas
- ✅ Validação de CPF matemática completa
- ✅ Rejeição de CPF duplicado
- ✅ Validação de todos os campos
- ✅ Proteção CSRF (tokens Laravel)
- ✅ SQL Injection prevenido (ORM Eloquent)

### Proteção de Dados
- ✅ Senhas não armazenadas (app pública)
- ✅ Relacionamentos de BD preservados
- ✅ Soft deletes (opção futura)
- ✅ Auditoria de ações

---

## 📈 Estatísticas Disponíveis

### Dashboard Mostra:
- 📊 Total de pessoas cadastradas
- 📊 Total de diagnósticos realizados
- 📊 Doenças detectadas
- 📊 Bairros afetados

### Gráficos:
- 📈 Ranking de doenças (gráfico de barras)
- 📈 Distribuição por bairro (gráfico pizza)
- 📈 Tabelas com detalhes

---

## ✨ Características Especiais

### Inteligência no Diagnóstico
```
Sistema de Pontuação:
├─ Cada sintoma tem peso para cada doença
├─ Exemplo: Febre + Tosse = Alto para Gripe
├─ Probab = (Pontos Obtidos / Pontos Máximos) × 100
└─ Resultado: 87.5% chance de Gripe
```

### Alerta Automático por Bairro
```
Monitora em tempo real:
├─ 10+ casos → Alerta Moderado ⚠️
├─ 20+ casos → Alerta Alto 🚨
├─ 30+ casos → Alerta Crítico 🔴
└─ < 10 casos → Situação Normal 🟢
```

---

## 📝 Arquivos Importantes

```
EpiMonitor definitivo/
├── app/
│   ├── Http/Controllers/
│   │   ├── PersonController.php
│   │   ├── DiagnosisController.php
│   │   └── StatisticsController.php
│   ├── Models/
│   │   ├── Person.php
│   │   ├── Disease.php
│   │   ├── Diagnosis.php
│   │   └── SymptomRecord.php
│   ├── Rules/
│   │   └── ValidCPF.php
│   ├── Services/
│   │   └── DiagnosisService.php
│   └── Providers/
│       └── AppServiceProvider.php (com validação CPF)
│
├── database/
│   ├── migrations/
│   │   ├── 2026_05_09_003513_create_people_table.php
│   │   ├── 2026_05_09_003532_create_diseases_table.php
│   │   ├── 2026_05_09_003547_create_diagnoses_table.php
│   │   └── 2026_05_09_003601_create_symptom_records_table.php
│   └── seeders/
│       └── DiseaseSeeder.php (popula 8 doenças)
│
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── people/
│   ├── diagnoses/
│   ├── statistics/
│   └── welcome.blade.php
│
├── routes/
│   └── web.php (todas as rotas)
│
└── SETUP.md (instruções)
```

---

## 🎓 Referência Original

Baseado em seu projeto C:
```
https://github.com/Pedromuzi-al/epimonitor-em-c
```

**Mantendo todas as funcionalidades:**
- ✅ Cadastro de pessoas
- ✅ 12 sintomas diferentes
- ✅ 8 doenças
- ✅ Sistema de pontuação
- ✅ Cálculo de probabilidade
- ✅ Alertas por bairro
- ✅ Estatísticas

---

## ⚠️ Disclaimer

> **Este é um sistema de simulação educacional**
>
> Os diagnósticos fornecidos NÃO substituem uma consulta médica profissional.
> Sempre procure um profissional de saúde qualificado para diagnóstico e tratamento.

---

## 🎉 Próximas Melhorias

- [ ] Autenticação de usuários
- [ ] Exportar PDF/Excel
- [ ] Integração com mapa
- [ ] Notificações por email
- [ ] API REST
- [ ] Dashboard admin
- [ ] Análise temporal (gráficos por período)
- [ ] Sugestões de ação por bairro

---

**Projeto estruturado e pronto para uso! 🚀**

Para dúvidas ou problemas, consulte o arquivo SETUP.md
