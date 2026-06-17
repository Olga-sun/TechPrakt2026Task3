<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/recipes')]
class RecipeController extends AbstractController
{
    // 1. GET: Отримати всі рецепти
    #[Route('', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository): JsonResponse
    {
        $recipes = $recipeRepository->findAll();
        $data = [];
        foreach ($recipes as $r) {
            $data[] = [
                'id' => $r->getId(),
                'title' => $r->getTitle(),
                'description' => $r->getDescription(),
                'cook_time' => $r->getCookTime()
            ];
        }
        return $this->json($data);
    }

    // 2. POST: Створити новий рецепт
    #[Route('', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $recipe = new Recipe();
        $recipe->setTitle($data['title']);
        $recipe->setDescription($data['description'] ?? null);
        $recipe->setCookTime($data['cook_time']);

        $em->persist($recipe);
        $em->flush();

        return $this->json([
            'id' => $recipe->getId(),
            'title' => $recipe->getTitle(),
            'description' => $recipe->getDescription(),
            'cook_time' => $recipe->getCookTime()
        ], 201);
    }

    // 3. GET: Отримати один рецепт
    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, RecipeRepository $recipeRepository): JsonResponse
    {
        $recipe = $recipeRepository->find($id);
        if (!$recipe) return $this->json(['message' => 'Не знайдено'], 404);

        return $this->json([
            'id' => $recipe->getId(),
            'title' => $recipe->getTitle(),
            'description' => $recipe->getDescription(),
            'cook_time' => $recipe->getCookTime()
        ]);
    }

    // 4. PATCH: Оновити рецепт
    #[Route('/{id}', methods: ['PATCH'])]
    public function update(int $id, Request $request, RecipeRepository $recipeRepository, EntityManagerInterface $em): JsonResponse
    {
        $recipe = $recipeRepository->find($id);
        if (!$recipe) return $this->json(['message' => 'Не знайдено'], 404);

        $data = json_decode($request->getContent(), true);
        if (isset($data['title'])) $recipe->setTitle($data['title']);
        if (isset($data['description'])) $recipe->setDescription($data['description']);
        if (isset($data['cook_time'])) $recipe->setCookTime($data['cook_time']);

        $em->flush();
        return $this->json(['message' => 'Успішно оновлено']);
    }

    // 5. DELETE: Видалити рецепт
    #[Route('/{id}', methods: ['DELETE'])]
    public function destroy(int $id, RecipeRepository $recipeRepository, EntityManagerInterface $em): JsonResponse
    {
        $recipe = $recipeRepository->find($id);
        if (!$recipe) return $this->json(['message' => 'Не знайдено'], 404);

        $em->remove($recipe);
        $em->flush();
        return $this->json(['message' => 'Успішно видалено']);
    }
}