<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY_Pagination Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Pagination
 * @author		Murzid
 * @link		https://github.com/murzid/codeigniter-pagination-mod
 */
class MY_Pagination extends CI_Pagination {

	public $query_url	= ''; // The additional page we are linking to
	
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 */
	public function __construct($params = array())
	{
		parent::__construct($params);	
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Generate the first record for LIMIT Query
	 *
	 * @current_page	public
	 * @max_per_page	public
	 * @return			int
	 */
	public function first_record($current_page, $max_per_page, $use_segment = TRUE)
	{
		$CI =& get_instance();
		
		// Use Segment's value
		if ($use_segment) {
			$this->cur_page	= $CI->uri->segment($current_page);
		
		// Manual set value
		} else {
			$this->cur_page	= $current_page;
		}
		
		$cur_page		= $this->cur_page;
		$cur_page		= (empty($cur_page) || $cur_page < 1) ? 1 : (int) $cur_page;
		return ($cur_page - 1) * $max_per_page;
	}

	// --------------------------------------------------------------------

	/**
	 * Return total of pagination links
	 *
	 * @access	public
	 * @return	string
	 */
	
	public function total_link() {
		// Calculate the total number of pages
		return ceil($this->total_rows / $this->per_page);
	}
	
	// --------------------------------------------------------------------
	
	 
	 /**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */
	public function create_links()
	{
		// If our item count or per-page total is zero there is no need to continue.
		if ($this->total_rows == 0 OR $this->per_page == 0)
		{
			return '';
		}

		// Calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);

		// Is there only one page? Hm... nothing more to do here then.
		if ($num_pages == 1)
		{
			return '';
		}

		$this->query_url = '/' . ltrim($this->query_url, '/');
		
		// Determine the current page number.
		$CI =& get_instance();
		
		$this->cur_page		= (int) $this->cur_page;
		$this->num_links	= (int)$this->num_links;

		if ($this->num_links < 1)
		{
			show_error('Your number of links must be a positive number.');
		}

		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = 1;
		}

		// Is the page number beyond the result range?
		// If so we show the last page
		
		if ($this->cur_page > $this->total_rows)
		{
			$this->cur_page = $num_pages;
		}
		
		if ($this->cur_page < 1)
		{
			$this->cur_page = 1;
		}
		
		$uri_page_number = $this->cur_page;
		
		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

		// Is pagination being used over GET or POST?  If get, add a per_page query
		// string. If post, add a trailing slash to the base URL if needed
		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			$this->base_url = rtrim($this->base_url).'&amp;'.$this->query_string_segment.'=';
		}
		else
		{
			$this->base_url = rtrim($this->base_url, '/') .'/';
		}

  		// And here we go...
		$output = '';

		// Render the "First" link
		if  ($this->cur_page > ($this->num_links + 1))
		{
			$output .= $this->first_tag_open.'<a href="'.$this->base_url.'1'.$this->query_url.'">'.$this->first_link.'</a>'.$this->first_tag_close;
		}

		// Render the "previous" link
		if  ($this->cur_page != 1)
		{
			$i	= $this->cur_page - 1;
			$output .= $this->prev_tag_open.'<a href="'.$this->base_url.$i.$this->query_url.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
		}

		// Write the digit links		
		for ($loop = $start; $loop <= $end; $loop++)
		{
			$i = $loop;

			if ($i >= 0)
			{
				if ($this->cur_page == $loop)
				{
					$output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
				}
				else
				{
					$n = $i;
					$output .= $this->num_tag_open.'<a href="'.$this->base_url.$n.$this->query_url.'">'.$loop.'</a>'.$this->num_tag_close;
				}
			}
		}

		// Render the "next" link
		if ($this->cur_page < $num_pages)
		{
			$output .= $this->next_tag_open.'<a href="'.$this->base_url.($this->cur_page + 1).$this->query_url.'">'.$this->next_link.'</a>'.$this->next_tag_close;
		}

		// Render the "Last" link
		if (($this->cur_page + $this->num_links) < $num_pages)
		{
			$i = $num_pages;
			$output .= $this->last_tag_open.'<a href="'.$this->base_url.$i.$this->query_url.'">'.$this->last_link.'</a>'.$this->last_tag_close;
		}

		// Kill double slashes.  Note: Sometimes we can end up with a double slash
		// in the penultimate link so we'll kill all double slashes.
		$output = preg_replace("#([^:])//+#", "\\1/", $output);

		// Add the wrapper HTML if exists
		$output = $this->full_tag_open.$output.$this->full_tag_close;

		return $output;
	}
}
// END Pagination Class

/* End of file MY_Pagination.php */
/* Location: ./aplication/libraries/MY_Pagination.php */