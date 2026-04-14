#include <algorithm>
#include <cmath> 

extern "C" void filter_gray(uint8_t *pixels, int width, int height)
{
    for (int i = 0; i < width * height; i++)
    {

        uint8_t r = pixels[i * 4 + 0];                                       // Valeur de rouge du pixel
        uint8_t g = pixels[i * 4 + 1];                                       // Valeur de vert du pixel
        uint8_t b = pixels[i * 4 + 2];                                       // Valeur de bleu du pixel
        int grayValue = static_cast<int>(0.299 * r + 0.587 * g + 0.114 * b); // Calcul de la valeur de gris
        // static_cast<int> est utilisé pour convertir le résultat en entier, car les valeurs de couleur sont généralement représentées par des entiers.

        // Met à jour les composantes de couleur du pixel avec la valeur de gris
        pixels[i * 4 + 0] = grayValue; // R
        pixels[i * 4 + 1] = grayValue; // G
        pixels[i * 4 + 2] = grayValue; // B
        // pixels[i * 4 + 3] = 255; // A (optionnel, si vous souhaitez conserver la composante alpha)
    }
}

// La formule
// Pour chaque pixel, la formule standard est : gris = 0.299 × R + 0.587 × G + 0.114 × B

extern "C" void filter_sepia(uint8_t *pixels, int width, int height)
{
    for (int i = 0; i < width * height; i++)
    {
        uint8_t r = pixels[i * 4 + 0]; // Valeur de rouge du pixel
        uint8_t g = pixels[i * 4 + 1]; // Valeur de vert du pixel
        uint8_t b = pixels[i * 4 + 2]; // Valeur de bleu du pixel
        int newR = static_cast<int>(0.393 * r + 0.769 * g + 0.189 * b);
        int newG = static_cast<int>(0.349 * r + 0.686 * g + 0.168 * b);
        int newB = static_cast<int>(0.272 * r + 0.534 * g + 0.131 * b);
        // Les valeurs de newR, newG et newB peuvent dépasser 255, il est donc important de les limiter à 255 pour éviter les débordements de couleur.
        pixels[i * 4 + 0] = std::min(255, newR); // R
        pixels[i * 4 + 1] = std::min(255, newG); // G
        pixels[i * 4 + 2] = std::min(255, newB); // B
    }

    // newR = (R × 0.393) + (G × 0.769) + (B × 0.189)
    // newG = (R × 0.349) + (G × 0.686) + (B × 0.168)
    // newB = (R × 0.272) + (G × 0.534) + (B × 0.131)
}

extern "C" void filter_invert(uint8_t *pixels, int width, int height)
{
    for (int i = 0; i < width * height; i++)
    {
        pixels[i * 4 + 0] = 255 - pixels[i * 4 + 0]; // Inversion de la composante rouge
        pixels[i * 4 + 1] = 255 - pixels[i * 4 + 1]; // Inversion de la composante verte
        pixels[i * 4 + 2] = 255 - pixels[i * 4 + 2]; // Inversion de la composante bleue
        // pixels[i * 4 + 3] = pixels[i * 4 + 3]; // La composante alpha reste inchangée
    }
}

extern "C" void filter_contrast(uint8_t *pixels, int width, int height, float factor)
{
    // ... newR = 128 + factor × (R - 128)
    // std::max(0, std::min(255, valeur))

    for (int i = 0; i < width * height; i++)
    {
        uint8_t r = pixels[i * 4 + 0]; // Valeur de rouge du pixel
        uint8_t g = pixels[i * 4 + 1]; // Valeur de vert du pixel
        uint8_t b = pixels[i * 4 + 2]; // Valeur de bleu du pixel

        int newR = static_cast<int>(128 + factor * (r - 128));
        int newG = static_cast<int>(128 + factor * (g - 128));
        int newB = static_cast<int>(128 + factor * (b - 128));

        pixels[i * 4 + 0] = std::max(0, std::min(255, newR)); // R
        pixels[i * 4 + 1] = std::max(0, std::min(255, newG)); // G
        pixels[i * 4 + 2] = std::max(0, std::min(255, newB)); // B
        // pixels[i * 4 + 3] = pixels[i * 4 + 3]; // La composante alpha reste inchangée
    }
}

extern "C" void filter_brightness(uint8_t *pixels, int width, int height, float factor)
{

    for (int i = 0; i < width * height; i++)
    {
        uint8_t r = pixels[i * 4 + 0]; // Valeur de rouge du pixel
        uint8_t g = pixels[i * 4 + 1]; // Valeur de vert du pixel
        uint8_t b = pixels[i * 4 + 2]; // Valeur de bleu du pixel
        int newR = static_cast<int>(r + factor);
        int newG = static_cast<int>(g + factor);
        int newB = static_cast<int>(b + factor);
        pixels[i * 4 + 0] = std::max(0, std::min(255, newR)); // R
        pixels[i * 4 + 1] = std::max(0, std::min(255, newG)); // G
        pixels[i * 4 + 2] = std::max(0, std::min(255, newB)); // B
        // pixels[i * 4 + 3] = pixels[i * 4 + 3]; // La composante alpha reste inchangée
    }
}

extern "C" void filter_zone(uint8_t *pixels, int width, int height, int clickX, int clickY, int radius)
{
    for( int y = 0; y < height ; y++) {
        for( int x = 0; x < width; x++) {
            // 1. Calculer la distance entre (x,y) et (clickX, clickY)
            int dx = x - clickX;
            int dy = y - clickY;
            float distance = std::sqrt(dx * dx + dy * dy);
            
            // 2. Si distance < radius → appliquer le filtre gray sur ce pixel
            if(distance < radius) {
                int i = (y * width + x) * 4; // Calcul de l'indice du pixel dans le tableau
                uint8_t r = pixels[i + 0]; // Valeur de rouge du pixel
                uint8_t g = pixels[i + 1]; // Valeur de vert du pixel
                uint8_t b = pixels[i + 2]; // Valeur de bleu du pixel
                int grayValue = static_cast<int>(0.299 * r + 0.587 * g + 0.114 * b); // Calcul de la valeur de gris
                pixels[i + 0] = grayValue; // R
                pixels[i + 1] = grayValue; // G
                pixels[i + 2] = grayValue; // B

            }

            // Indice : distance = sqrt((x-clickX)² + (y-clickY)²)
            // En C++ : #include <cmath> et std::sqrt()
        }
    }
}