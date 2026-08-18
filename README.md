<div align="center">
  <img src="public/assets/img/logo.png" alt="Logo do Pelicano Lava-Jato JF" width="220" />

# Pelicano Lava-Jato JF

**Site institucional e sistema de gestão desenvolvidos para uma operação real de estética automotiva.**
</div>

## Sobre o projeto

O projeto reúne a presença digital do Pelicano Lava-Jato JF e um painel administrativo para apoiar a rotina do negócio. A área pública apresenta a empresa e seus serviços; a área restrita organiza clientes, veículos, agendamentos e ordens de serviço.

Este repositório é uma versão de portfólio. Credenciais, dados da empresa, registros de clientes, seeds operacionais e vídeos reais de veículos não fazem parte do código publicado.

## Funcionalidades

- Página institucional responsiva com catálogo de serviços e contato por WhatsApp.
- Autenticação administrativa e recuperação de senha por SMTP.
- Cadastro e manutenção de clientes e veículos.
- Agenda diária de serviços.
- Criação, edição, impressão e acompanhamento de ordens de serviço.
- Gestão dos dados da empresa e dos serviços oferecidos.
- Proteção CSRF, sessões PHP e consultas preparadas com PDO.

## Tecnologias

- PHP 8 com tipagem estrita.
- MySQL ou MariaDB com PDO.
- HTML, CSS e JavaScript sem framework no frontend.
- Apache com regras de reescrita por `.htaccess`.
- SMTP para recuperação de senha.

## Estrutura

```text
.
├── app/                         # domínio, autenticação, banco, e-mail e views
├── database/schema.example.sql # estrutura do banco sem dados reais
├── public/                      # entrada web e ativos públicos
├── config.example.php          # modelo seguro de configuração local
├── config.php                  # configuração real, ignorada pelo Git
└── index.php                   # entrada da aplicação
```

## Executando localmente

### Pré-requisitos

- PHP 8.1 ou superior com extensão PDO MySQL.
- MySQL ou MariaDB.

### Configuração

1. Copie `config.example.php` para `config.php`.
2. Preencha as credenciais locais de banco e, opcionalmente, SMTP.
3. Crie um banco e importe `database/schema.example.sql`.
4. Cadastre a empresa e um administrador diretamente no ambiente local.

Para gerar um hash seguro para a senha inicial:

```bash
php -r "echo password_hash('troque-esta-senha', PASSWORD_DEFAULT), PHP_EOL;"
```

Inicie o servidor de desenvolvimento na raiz do projeto:

```bash
php -S localhost:8080 index.php
```

## Mídias não incluídas

Arquivos MP4 e a pasta de vídeos reais foram deliberadamente excluídos para reduzir o repositório e proteger imagens de veículos de clientes. Em uma instalação própria, substitua os caminhos retornados por `site_media()` em `app/helpers.php` por mídias autorizadas.

## Segurança e privacidade

- `config.php` nunca deve ser versionado.
- Não publique dumps, seeds de produção, bancos ou backups.
- Use um usuário de banco exclusivo e uma senha forte.
- Mantenha credenciais SMTP apenas no ambiente ou no arquivo local ignorado.
- Remova ou anonimimize placas, telefones e dados de clientes antes de compartilhar mídias ou bases.

## Desenvolvimento com apoio de IA

Ferramentas de inteligência artificial generativa foram usadas como apoio no planejamento, implementação, revisão e documentação. As decisões de produto, a validação funcional e a responsabilidade pela entrega permaneceram sob revisão humana.

## Autoria e uso

Desenvolvido por [@jefilds2](https://github.com/jefilds2) como projeto real e peça de portfólio.

A marca, os logotipos, fotografias e demais materiais comerciais pertencem aos seus respectivos titulares e não estão licenciados para reutilização.
