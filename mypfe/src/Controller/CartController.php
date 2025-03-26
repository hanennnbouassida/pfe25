<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\Client;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;



class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);
        $cartDetails = [];
        $total = 0;

        // Prepare cart data with additional product info if needed
        foreach ($cart as $productId => $item) {
            $product = $productRepository->find($productId);
            $cartDetails[$productId] = [
                'id' => $productId,
                'name' => $item['name'],
                'price' => $item['price'],
                'image' => $item['image'],
                'quantity' => $item['quantity'],
                'product' => $product // Optional: only if you need full entity in template
            ];
            $total += $item['price'] * $item['quantity'];
        }

        return $this->render('product/cart.html.twig', [
            'cart' => $cartDetails,
            'total' => $total
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function addToCart(Product $product, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
    
        // Calculate discounted price
        $discount = $product->getDiscountPercentage(); // Assuming getDiscount() method exists
        $finalPrice = $discount > 0 ? $product->getPrice() * (1 - $discount / 100) : $product->getPrice();
    
        // Add product to cart
        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()]['quantity']++;
        } else {
            $cart[$product->getId()] = [
                'product' => $product,
                'quantity' => 1,
                'price' => $finalPrice // Store discounted price
            ];
        }
    
        $session->set('cart', $cart);
    
        return $this->redirectToRoute('cart');
    }
    

    #[Route('/cart/increase/{id}', name: 'cart_increase')]
    public function increase(Product $product, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $productId = $product->getId();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
            $session->set('cart', $cart);
            $this->addFlash('success', 'Quantity increased!');
        }

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/decrease/{id}', name: 'cart_decrease')]
    public function decrease(Product $product, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $productId = $product->getId();

        if (isset($cart[$productId])) {
            if ($cart[$productId]['quantity'] > 1) {
                $cart[$productId]['quantity']--;
                $this->addFlash('success', 'Quantity decreased!');
            } else {
                unset($cart[$productId]);
                $this->addFlash('warning', 'Product removed from cart!');
            }
            $session->set('cart', $cart);
        }

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(Product $product, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $productId = $product->getId();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $session->set('cart', $cart);
            $this->addFlash('warning', 'Product removed from cart!');
        }

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/clear', name: 'cart_clear')]
    public function clear(SessionInterface $session): Response
    {
        $session->set('cart', []);
        $this->addFlash('warning', 'Cart cleared!');
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/update', name: 'cart_update', methods: ['POST'])]
    public function update(Request $request, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $quantities = $request->request->all()['quantity'];

        foreach ($quantities as $productId => $quantity) {
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = max(1, (int)$quantity);
            }
        }

        $session->set('cart', $cart);
        $this->addFlash('success', 'Cart updated!');
        return $this->redirectToRoute('cart');
    }


/*
    #[Route('/cart/create-order', name: 'cart_create_order')]
    public function createOrder(SessionInterface $session, EntityManagerInterface $em, ProductRepository $productRepo)
    {
        $client = $this->getUser();
        if (!$client) {
            throw $this->createAccessDeniedException('User not authenticated.');
        }
    
        $cart = $session->get('cart', []);
        if (empty($cart)) {
            $this->addFlash('error', 'Your cart is empty.');
            return $this->redirectToRoute('cart');
        }
    
        $order = new Commande();
        $order->setClient($client);
        $order->setCommandeDate(new \DateTime());
        $order->setStatus('Pending');
    
        $total = 0;
        foreach ($cart as $productId => $item) {
            // Get fresh product reference from database
            $product = $em->getReference(Product::class, $productId);
            // OR: $product = $productRepo->find($productId);
            
            $price = $product->getPrice() * $item['quantity'];
    
            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setQuantity($item['quantity']);
            $orderItem->setPrice($price);
            $orderItem->setOrder($order);
    
            $total += $price;
            $em->persist($orderItem);
        }
    
        $order->setTotalPrice($total);
        $em->persist($order);
        $em->flush();
    
        $session->remove('cart');
        $this->addFlash('success', 'Order created successfully.');
        return $this->redirectToRoute('order_show', ['id' => $order->getId()]);
    }

*/
    
}
