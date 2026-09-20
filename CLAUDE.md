# Zunftfolge (zunftfolge.de)

Nachfolge-Vermittlung für kleine Handwerksbetriebe in Deutschland — ein
Geschäftsbereich der Plotter Fix GmbH (Herne). Zielgruppe: Betriebsinhaber
kurz vor dem Ruhestand ohne Nachfolger, und kapitalkräftige Nachfolger.

## Tech-Stack

- Statisches HTML/CSS + PHP-Formularhandler. Kein Build-Schritt, kein
  npm/React/Bundler.
- Styling: gemeinsames `css/style.css` (CSS custom properties in `:root`)
  plus inline `style=""` auf den einzelnen Seiten.
- Lokaler Dev-Server: `php -S 127.0.0.1:8000` im Projektroot.
- Scroll-Animationen: GSAP + ScrollTrigger via CDN (cdnjs), siehe
  `js/reveal.js`. Guarded durch `prefers-reduced-motion` und
  `typeof gsap === 'undefined'`-Check, damit die Seite ohne JS/CDN
  vollständig nutzbar bleibt.

## Design-Richtung

Hell, redaktionell, zurückhaltend — soll wie eine seriöse M&A-/Kanzlei-Seite
wirken, nicht wie eine generische KI-generierte SaaS-Landingpage
("AI slop"). Konkret:

- Serif: Newsreader (Überschriften/Zitate), Sans: Work Sans (Fließtext/UI).
- Akzentfarben: gedecktes Waldgrün + mattes Terrakotta (oklch-Werte in
  `css/style.css`), kein Neon, kein Firmen-Blau.
- Scharfe Radien (2–4px), keine Pillen-Badges — Labels sind unterstrichener
  Großbuchstaben-Text statt farbiger Kapseln.
- Keine Deko-Icons ohne Funktion, keine Icon-in-Kreis-Muster.
- Animationen sind subtil (Fade+Slide beim Scrollen), nie Pin/Scrub/
  Parallax/Scrollytelling — das würde die Seriosität untergraben.

**Anti-Slop-Faustregel:** Wenn ein Element ohne inhaltlichen Verlust entfernt
werden könnte, gehört es nicht rein. Jede Animation braucht einen Grund.

## Projekt-Skills (`.claude/skills/`)

- **gsap-scrolltrigger** — Referenz für die vorhandene GSAP/ScrollTrigger-
  Nutzung. Nur die schlichten Patterns (Fade-In-On-Scroll, ggf. Stagger via
  `ScrollTrigger.batch`) sind hier passend — Pin/Scrub/Parallax/
  Horizontal-Scroll/Image-Sequence-Scrubbing NICHT verwenden.
- **modern-web-design** — Meta-Referenz. Nur folgende Abschnitte sind hier
  relevant: Accessibility (prefers-reduced-motion, Kontrast, Fokus-Styles,
  Touch-Targets) und Performance (GPU-animierte Properties, Lazy Loading,
  responsive Bilder) sowie "Common Pitfalls" (v. a. Pitfall 1
  "Over-Animation" und Pitfall 6 "Scroll Hijacking"). Die "Common Design
  Patterns" (Custom Cursor, Glassmorphism, immersive 3D-Hero, Page-
  Transitions via Barba.js, Scrollytelling) passen NICHT zu diesem Projekt
  und sollen nicht eingebaut werden, auch wenn der Skill sie referenziert.

**Bewusst nicht übernommen** (aus der größeren Skill-Sammlung des Users,
geprüft und für dieses Projekt verworfen, da sie Richtung 3D/VR/
Scrollytelling/React gehen und der "seriös, kein Gimmick"-Anforderung
widersprechen oder einen Tech-Stack voraussetzen, den es hier nicht gibt):
aframe-webxr, babylonjs-engine, playcanvas-engine, pixijs-2d,
animated-component-libraries, motion-framer, animejs, barba-js,
locomotive-scroll, lightweight-3d-effects, lottie-animations,
blender-web-pipeline, react-spring-physics, react-three-fiber,
rive-interactive, scroll-reveal-libraries, spline-interactive,
substance-3d-texturing, threejs-webgl, web3d-integration-patterns.
Falls für ein *anderes* Projekt des Users relevant (z. B. `robomatch-design`
ist explizit nur fürs RoboMatch-Projekt gedacht), hier nicht anwenden.

## Offene Punkte

- `impressum.html` hat noch Platzhalter (`[Vor- und Nachname des
  Betreibers]`) — echte Firmendaten der Plotter Fix GmbH (Rechtsform,
  Adresse, Registergericht+HRB, USt-IdNr, Geschäftsführer) fehlen noch.
- Strukturelle "Anti-Slop"-Überarbeitung (Font, Radien, Badges,
  Deko-Icon-Entfernung) ist inzwischen auf allen 15 Seiten angewendet. Der
  edge-to-edge-Hero-Bildschnitt aus `index.html` wurde bewusst nicht auf die
  anderen Seiten übertragen (deren Hero-Bilder bleiben in der klassischen
  gerahmten Box, nur mit geschärftem Radius) — das wäre eine größere
  Layout-Änderung pro Seite und stand nicht explizit zur Freigabe.
