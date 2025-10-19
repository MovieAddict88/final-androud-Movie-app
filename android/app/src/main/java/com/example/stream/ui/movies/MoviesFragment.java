package com.example.stream.ui.movies;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.GridLayoutManager;

import com.example.stream.databinding.FragmentListBinding;
import com.example.stream.data.model.ContentItem;
import com.example.stream.data.net.ApiClient;
import com.example.stream.ui.home.PostersAdapter;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MoviesFragment extends Fragment {
    public static MoviesFragment newInstance() { return new MoviesFragment(); }

    private FragmentListBinding binding;
    private PostersAdapter adapter;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        binding = FragmentListBinding.inflate(inflater, container, false);
        binding.recycler.setLayoutManager(new GridLayoutManager(getContext(), 3));
        adapter = new PostersAdapter(new ArrayList<ContentItem>());
        binding.recycler.setAdapter(adapter);
        load();
        return binding.getRoot();
    }

    private void load() {
        ApiClient.getInstance().getContents("movie", null, 1, 50).enqueue(new Callback<Map<String, Object>>() {
            @Override public void onResponse(Call<Map<String, Object>> call, Response<Map<String, Object>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    Object list = response.body().get("data");
                    List<?> items = list instanceof List ? (List<?>) list : new ArrayList<>();
                    List<ContentItem> casted = cast(items);
                    adapter.submit(casted);
                }
            }
            @Override public void onFailure(Call<Map<String, Object>> call, Throwable t) {}
        });
    }

    private List<ContentItem> cast(List<?> items) {
        List<ContentItem> casted = new ArrayList<>();
        for (Object o : items) {
            if (o instanceof Map) {
                Map<?,?> m = (Map<?,?>) o;
                ContentItem c = new ContentItem();
                Object id = m.get("id");
                c.id = id instanceof Number ? ((Number) id).intValue() : 0;
                c.title = String.valueOf(m.get("title"));
                c.type = String.valueOf(m.get("type"));
                c.poster_url = m.get("poster_url") != null ? String.valueOf(m.get("poster_url")) : null;
                c.video_url = m.get("video_url") != null ? String.valueOf(m.get("video_url")) : null;
                casted.add(c);
            }
        }
        return casted;
    }
}
