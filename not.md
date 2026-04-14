# Projet WasmLighttom

## Étape 1 — La fondation : Canvas → Wasm (~1h)

Afficher une image dans un `<canvas>`, extraire ses pixels avec ImageData, les pousser dans la `WebAssembly.Memory`. C'est le tuyau central — tout le reste en dépend.

```
- <script type="module" defer>
```

### Génère une image de pixels aléatoires et l'affiche sur le canvas

```
    const canvas = document.getElementById("canvas_pixels_as");
    const ctx = canvas.getContext("2d");
    const fpsDisplay = document.getElementById("fps_display");
```
- Variables globales pour la largeur, la hauteur, le buffer d'image et l'ID de l'animation

```
    let width = canvas.width;
    let height = canvas.height;
    let imageBuffer;
    let animationId;
```


- Fonction pour générer des pixels aléatoires dans le buffer d'image
```
function generate_pixels_js(w, h) {
        for (let i = 0; i < w * h * 4; i++) {
          imageBuffer[i] = Math.floor(Math.random() * 256);
        }
      }
```
- Événement de clic pour démarrer l'animation de la pluie de pixels

```
document.getElementById("btn_pixel_as").addEventListener("click", () => {
        if (animationId) {
          cancelAnimationFrame(animationId);
        }
)
```         
- Récupère les dimensions du canvas à partir des champs de saisie et redimensionne le canvas
```
width = parseInt(document.getElementById("canvas_width").value);
        height = parseInt(document.getElementById("canvas_height").value);
        canvas.width = width;
        canvas.height = height;
```
- Alloue un buffer d'image pour stocker les pixels aléatoires
```
 const neededSize = width * height * 4;
        imageBuffer = new Uint8ClampedArray(neededSize);
```

- Fonction de rendu qui génère les pixels, met à jour le canvas et calcule le FPS
    - Met à jour le FPS toutes les secondes
    - Génère les pixels aléatoires, crée un ImageData et l'affiche sur le canvas
          generate_pixels_js(width, height);
```
function render() {
          const now = performance.now();
          frameCount++;

          if (now - lastTime >= 1000) {
            fpsDisplay.innerText = `FPS: ${frameCount}`;
            frameCount = 0;
            lastTime = now;
          }
          generate_pixels_js(width, height);
          const imageData = new ImageData(imageBuffer, width, height);
          ctx.putImageData(imageData, 0, 0);
          animationId = requestAnimationFrame(render);
        }

        render();

```
- Coté HTML: Balise canvas :  Canvas pour afficher la pluie de pixels générée par le code JavaScript
```
<canvas id="canvas_pixels_as" width="400" height="300" style="border: 1px solid black; margin-top: 10px"></canvas>
```






Étape 2 — Le premier filtre : Niveaux de gris (~1h)
Un seul filtre C++ qui prend les pixels en mémoire et les transforme. Tu verras immédiatement le résultat, c'est super motivant.

Étape 3 — Les autres filtres globaux (~2h)
Sépia, Inversion, Contraste, Luminosité. Une fois le premier fait, les autres suivent vite.
Étape 4 — La retouche par zone (~1h30)
Détecter le clic sur le canvas, calculer la distance euclidienne pour chaque pixel, appliquer le filtre seulement dans le rayon.
Étape 5 — L'historique Undo/Redo (~1h30)
Sauvegarder l'état des pixels avant chaque filtre, permettre d'annuler. C'est le TP de la Séance 4 (le GestionnaireHistorique).
Étape 6 — Le Backend PHP (~4-5h)
Login, upload, base de données, feed, la "recette" JSON sauvegardée en BDD.