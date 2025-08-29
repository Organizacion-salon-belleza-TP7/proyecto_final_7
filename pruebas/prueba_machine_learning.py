import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity

# Datos de ejemplo (usuarios calificando películas de 1 a 5)
data = {
    "Usuario": ["A", "A", "A", "B", "B", "C", "C", "C", "D"],
    "Pelicula": ["Matrix", "Inception", "Titanic", "Matrix", "Titanic",
                 "Inception", "Titanic", "Avatar", "Matrix"],
    "Calificacion": [5, 4, 2, 5, 3, 4, 5, 3, 4]
}

# Convertir a DataFrame
df = pd.DataFrame(data)

# Crear una tabla de usuarios vs películas
tabla = df.pivot_table(index="Usuario", columns="Pelicula", values="Calificacion").fillna(0)
print("Matriz Usuario-Película:\n", tabla)

# Calcular similitud entre usuarios (coseno)
similitud = cosine_similarity(tabla)
similitud_df = pd.DataFrame(similitud, index=tabla.index, columns=tabla.index)
print("\nSimilitud entre usuarios:\n", similitud_df)

# --- Ejemplo: recomendarle a usuario D ---
usuario = "D"

# Encontrar el usuario más parecido a D
usuario_similar = similitud_df[usuario].drop(usuario).idxmax()
print(f"\nUsuario más parecido a {usuario}: {usuario_similar}")

# Películas que el usuario similar vio pero D no
peliculas_usuario = set(tabla.loc[usuario][tabla.loc[usuario] > 0].index)
peliculas_similar = set(tabla.loc[usuario_similar][tabla.loc[usuario_similar] > 0].index)

recomendaciones = peliculas_similar - peliculas_usuario
print(f"Recomendaciones para {usuario}: {recomendaciones}")
