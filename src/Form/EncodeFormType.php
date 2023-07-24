<?php

namespace App\Form;

use App\Entity\CodeUrlPair;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EncodeFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('url', UrlType::class, [
				'label' => 'Enter your url:',
				'attr' => [
					'placeholder' => 'url'
				]
			])
			->add('save', SubmitType::class, [
				'attr' => [
					'value' => 'Encode',
					'type'  => 'submit'
				]
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => CodeUrlPair::class,
		]);
	}
}
