<?php
/**
 * @param int $no_of_codes//���������Ż��������
 * @param array $exclude_codes_array//
 * @param int $code_length//�Ż���ĳ���
 * @return array //��������
 */
function generate_promotion_code($no_of_codes,$exclude_codes_array='',$code_length = 4)
{
	$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

	$promotion_codes = array();
	for($j = 0 ; $j < $no_of_codes; $j++)
	{
		$code = "";	
		for ($i = 0; $i < $code_length; $i++) 
		{
			$code .= $characters[mt_rand(0, strlen($characters)-1)];
		}
		
		if(!in_array($code,$promotion_codes))
		{
			if(is_array($exclude_codes_array))
			{
				if(!in_array($code,$exclude_codes_array))
				{
					$promotion_codes[$j] = $code;
				}
				else
				{
					$j--;		
				}	
			}	
			else
			{
				$promotion_codes[$j] = $code;
			}
		}
		else
		{
			$j--;	
		}
	}
	return $promotion_codes;	
}
echo '<h1>���� /�Ż���ʵ��ÿ��ˢ��ҳ�涼���������</h1>';
echo '<pre>';
print_r(generate_promotion_code(50,'',6));
echo '</pre>';
?>