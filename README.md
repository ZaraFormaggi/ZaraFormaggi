# 💼 Zara Formaggi – Sistema Ordini + Pagamenti con SumUp

Questo progetto implementa un sistema completo per la ricezione ordini dal sito web e pagamento automatico tramite **SumUp**.

---

## 📦 Contenuto del progetto

| File | Descrizione |
|------|-------------|
| `sumup-config.php` | Inserisci qui le credenziali e access token SumUp |
| `sumup-link-generator.php` | Funzione per generare un link di pagamento con le API SumUp |
| `invia-ordine.php` | Riceve i dati del form, genera il link e invia l'email al cliente |
| `callback.php` | Ottiene il `access_token` dopo login SumUp (solo una volta) |

---

## 🧾 Come funziona

1. Il cliente compila un modulo d’ordine sul sito
2. Il backend PHP genera un link SumUp
3. Il cliente riceve via email il link per il pagamento
4. Tu ricevi copia dell’ordine

---

## 🔐 Pagamento con SumUp – Come configurarlo

### 1. Crea una app su SumUp

- Vai su: [https://me.sumup.com/developers](https://me.sumup.com/developers)
- Clicca **Crea App**
- Inserisci:
  - Nome app: Zara Formaggi
  - Redirect URI: `https://tuodominio.it/callback.php`

### 2. Inserisci le credenziali in `sumup-config.php`

```php
define('SUMUP_CLIENT_ID', 'IL_TUO_CLIENT_ID');
define('SUMUP_CLIENT_SECRET', 'IL_TUO_CLIENT_SECRET');
define('SUMUP_REDIRECT_URI', 'https://tuodominio.it/callback.php');
define('SUMUP_ACCESS_TOKEN', 'INSERISCI_DOPO');
```

---

## 🔁 Come ottenere `access_token`

1. Apri nel browser:

```
https://api.sumup.com/authorize?response_type=code&client_id=IL_TUO_CLIENT_ID&redirect_uri=https://tuodominio.it/callback.php&scope=checkout
```

2. Dopo login, verrai reindirizzato a `callback.php`
3. Copia il `access_token` mostrato a video
4. Incollalo in `sumup-config.php`

---

## ✉️ Come funziona `invia-ordine.php`

Riceve i dati inviati da un form come:

```html
<form action="invia-ordine.php" method="POST">
  <input type="text" name="nome" required>
  <input type="email" name="email" required>
  <input type="text" name="telefono">
  <input type="hidden" name="modalita" value="ritiro">
  <input type="hidden" name="ordine" value="Prodotto A x2">
  <input type="hidden" name="totale" value="34.90">
  <button type="submit">Invia Ordine</button>
</form>
```

Poi:
- Genera un link SumUp
- Invia email al cliente con il link
- Invia copia anche a te

---

## 🛡️ Sicurezza e GitHub

- **Non caricare** `sumup-config.php` su GitHub pubblico
- Usa un file `.gitignore`:

```
sumup-config.php
```

- Oppure crea `sumup-config-example.php` con valori fittizi

---

## ✅ Da testare

- [ ] Il form invia correttamente i dati?
- [ ] Ricevi le email?
- [ ] Il cliente riceve il link e può pagare?
- [ ] Funziona con ritiro e spedizione?

---

Creato con ❤️ per Zara Formaggi
